<?php

namespace App\Http\Controllers\Project;

use App\Models\Project\Project;
use App\Models\Project\ProjectEvent;
use App\Models\Project\Style;
use App\Models\Service\SpecialistService;
use App\Models\User;

class AutoCreateEvent
{

    public function createEvent($projectId, $newData)
    {
        $messageEvent = $this->getMessageEvent(
            $this->getOldData($projectId), $newData
        );

        if (!is_null($messageEvent)) {
            ProjectEvent::on()->create([
                'project_id' => $projectId,
                'date'       => now()->format('Y-m-d'),
                'comment'    => $messageEvent,
            ]);
        }
    }

    /*
     * Получить информацию
     */
    private function getOldData($projectId)
    {
        return Project::on()->with([
            'projectAuthor'
        ])->find($projectId)->toArray();
    }

    private function getMessageEvent($oldDate, $newDate)
    {

        $arrayData = [];

        if (isset($oldDate['project_author']))
            $oldDate['author_id'] = collect($oldDate['project_author'])->pluck('id')->toArray();


        if ($oldDate['manager_id'] != $newDate['manager_id']) { // менеджер
            $managers = User::on()->whereIn('id', [$oldDate['manager_id'], $newDate['manager_id']])->get();

            $old = $managers->whereIn('id', $oldDate['manager_id'])->first()->full_name ?? '';
            $new = $managers->whereIn('id', $newDate['manager_id'])->first()->full_name ?? '';

            $arrayData[] = [
                'name'      => 'Менеджер',
                'old_value' => $old,
                'new_value' => $new
            ];
        }

        if ($oldDate['style_id'] != $newDate['style_id']) { // Приоритетность

            $styles = Style::on()->whereIn('id', [$oldDate['style_id'], $newDate['style_id']])->get();

            $old = $styles->whereIn('id', $oldDate['style_id'])->first()->name ?? '';
            $new = $styles->whereIn('id', $newDate['style_id'])->first()->name ?? '';

            $arrayData[] = [
                'name'      => 'Приоритетность',
                'old_value' => $old,
                'new_value' => $new
            ];
        }

        if ($oldDate['task_client'] != $newDate['task_client']) { // Задача заказчика

            $arrayData[] = [
                'name'      => 'Задача заказчика',
                'old_value' => $oldDate['task_client'],
                'new_value' => $newDate['task_client']
            ];
        }


        if ($oldDate['type_task'] != $newDate['type_task']) { // Задача проекта

            $arrayData[] = [
                'name'      => 'Задача проекта',
                'old_value' => $oldDate['type_task'],
                'new_value' => $newDate['type_task']
            ];
        }

        if (($oldDate['price_client'] != $newDate['price_client'])) { // Цена заказчика

            $arrayData[] = [
                'name'      => 'Цена заказчика',
                'old_value' => $oldDate['price_client'],
                'new_value' => $newDate['price_client']
            ];
        }

        if ($oldDate['price_author'] != $newDate['price_author']) { // Цена автора

            $arrayData[] = [
                'name'      => 'Цена автора',
                'old_value' => $oldDate['price_author'],
                'new_value' => $newDate['price_author']
            ];
        }

        if ($this->arrayDiff($oldDate['author_id'] ?? [], $newDate['author_id'] ?? [])) { // Цена автора

            $ids = collect(array_merge($oldDate['author_id'], $newDate['author_id']))->map(function ($item) {
                return (int)$item;
            })->unique()->values()->toArray();

            $authors = User::on()->whereIn('id', $ids)->get();

            $old = implode(', ', $authors->whereIn('id', $oldDate['author_id'])->pluck('full_name')->toArray());
            $new = implode(', ', $authors->whereIn('id', $newDate['author_id'])->pluck('full_name')->toArray());

            $arrayData[] = [
                'name'      => 'Назначить авторов',
                'old_value' => $old,
                'new_value' => $new
            ];
        }

        if ($oldDate['leading_specialist_id'] != $newDate['leading_specialist_id']) { // ведущий специалист

            $oldValue = is_null($oldDate['leading_specialist_id']) ? '' : SpecialistService::on()->find($oldDate['leading_specialist_id'])?->name ?? '';
            $newValue = is_null($newDate['leading_specialist_id']) ? '' : SpecialistService::on()->find($newDate['leading_specialist_id'])?->name ?? '';

            $arrayData[] = [
                'name'      => 'Ведущий специалист',
                'old_value' => $oldValue,
                'new_value' => $newValue
            ];
        }

        $message = count($arrayData) > 0 ? $this->renderTable($arrayData) : null;

        return $message;
    }

    private function renderTable($arrayData)
    {

        $message = ' <table class="project-event-table"><tbody>';

        foreach ($arrayData as $value) {
            $message .= '
                <tr>
                    <td>' . $value['name'] . '</td>
                    <td class="old-value">' . $value['old_value'] . '</td>
                    <td class="new-value">' . $value['new_value'] . '</td>
                </tr>';
        }

        $message .= '</tbody></table>';

        return $message;
    }

    private
    function arrayDiff($array1, $array2)
    {
        $diff = false;
        if (count($array1) == count($array2)) {
            foreach ($array1 as $item) {
                if (!in_array($item, $array2)) {
                    $diff = true;
                    break;
                }
            }
            foreach ($array2 as $item) {
                if (!in_array($item, $array1)) {
                    $diff = true;
                    break;
                }
            }
        } else {
            $diff = true;
        }

        return $diff;
    }
}
