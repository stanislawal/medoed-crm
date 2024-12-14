<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Client\SocialNetwork;
use App\Models\Client\SourceClient;
use App\Models\Project\Cross\CrossClientSocialNetwork;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::on()
            ->with([
                'socialNetwork', 'projectClients', 'sourceClient'
            ]);
        $this->filter($request, $clients);

        $clients = $clients->orderBy('id', 'desc')
            ->paginate(50);

        $socialNetwork = SocialNetwork::on()
            ->get()
            ->toArray();

        $projects = Project::on()->select(['id', 'project_name'])->get();

        $sourceClients = SourceClient::on()->get();

        return view('client.list_clients', [
            'clients'        => $clients,
            'social_network' => $socialNetwork,
            'projects'       => $projects,
            'sourceClients' => $sourceClients
        ]);
    }

    public function create()
    {
        $socialNetwork = SocialNetwork::on()->get();
        $sourceClients = SourceClient::on()->get();

        return view('client.client', [
            'socialNetwork' => $socialNetwork,
            'sourceClients' => $sourceClients
        ]);
    }

    public function store(Request $request)
    {
        $attr = [
            'name'             => $request->name,
            'dialog_location'  => $request->dialog_location ?? null,
            'scope_work'       => $request->scope_work ?? null,
            'characteristic'   => $request->characteristic ?? null,
            'company_name'     => $request->company_name ?? null,
            'site'             => $request->site ?? null,
            // 'link_socialnetwork' => $request->link_socialnetwork ?? null,
            'contact_info'     => $request->contact_info ?? null,
            'birthday'         => $request->birthday ?? null,
            'source_client_id' => $request->source_client_id ?? null,

            'lpr_contacts'    => $request->lpr_contacts ?? null,
            'info_work_team'  => $request->info_work_team ?? null,
            'additional_info' => $request->additional_info ?? null,
        ];
        $client = Client::on()->create($attr);

        $socialnetworks = json_decode($request->socialnetwork_info, TRUE) ?? [];

        if (count($socialnetworks) > 0) {
            $attr = [];
            foreach ($socialnetworks as $item) {
                $attr[] = [
                    'client_id'         => $client->id,
                    'social_network_id' => $item['socialnetrowk_id'],
                    'description'       => $item['link'],
                ];
            }
            CrossClientSocialNetwork::on()->insert($attr);
        }

        return redirect()->back()->with(['success' => 'Заказчик успешно создан']);
    }

    public function show($id)
    {
        return abort(404);
    }

    public function edit($client)
    {
        $sourceClients = SourceClient::on()->get();

        $clients = Client::on()
            ->with('files')
            ->find($client)
            ->toArray();

        $socialNetwork = SocialNetwork::on()
            ->get()
            ->toArray();

        return view('client.client_edit', [
            'clients'       => $clients,
            'socialNetwork' => $socialNetwork,
            'sourceClients' => $sourceClients
        ]);
    }

    public function update(Request $request, $client)
    {
        $attr = collect($request)->only([
            'name',
            'scope_work',
            'company_name',
            'site',
            'link_socialnetwork',
            'contact_info',
            'characteristic',
            'lpr_contacts',
            'info_work_team',
            'additional_info',
            'source_client_id',
        ])->toArray();

        Client::on()->where('id', $client)->update($attr);

        CrossClientSocialNetwork::on()->where('client_id', $client)->delete();

        $socialnetworks = json_decode(($request->socialnetwork_info ?? '[]'), TRUE);

        if (count($socialnetworks) > 0) {
            $attr = [];
            foreach ($socialnetworks as $item) {
                $attr[] = [
                    'client_id'         => $client,
                    'social_network_id' => $item['socialnetrowk_id'],
                    'description'       => $item['link'],
                ];
            }
            CrossClientSocialNetwork::on()->insert($attr);
        }

        return redirect()->back()->with(['success' => 'Данные успешно обновлены.']);
    }

    public function destroy($client)
    {
        Client::on()->where('id', $client)->delete();
        return redirect()->back()->with(['success' => 'Заказчик успешно удален']);
    }


    private function filter($request, &$clients)
    {
        $clients->when(!empty($request->name), function (Builder $where) use ($request) {
            $where->where('name', 'like', '%' . $request->name . '%');
        });

        $clients->when(!empty($request->project_id), function (Builder $where) use ($request) {
            $where->whereHas('projectClients', function ($where) use ($request) {
                $where->whereIn('projects.id', $request->project_id);
            });
        });

        $clients->when(!empty($request->source_client_id), function (Builder $where) use ($request) {
            $where->where('source_client_id', $request->source_client_id);
        });
    }

}
