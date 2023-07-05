<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use App\Models\Client\SocialNetwork;
use App\Models\Project\Cross\CrossClientSocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {


        $clients = Client::on()
            ->with([
                'socialNetwork', 'projectClients'
            ]);

        $this->filter($request, $clients);

        $clients = $clients->orderBy('id', 'desc')
            ->get()
            ->toArray();

        $socialNetwork = SocialNetwork::on()->get()->toArray();




        return view('client.list_clients', [
            'clients' => $clients,
            'social_network' => $socialNetwork
        ]);
    }

    public function create()
    {
        $socialNetwork = SocialNetwork::on()->get();

        return view('client.client', [
            'socialNetwork' => $socialNetwork,
        ]);
    }

    public function store(Request $request)
    {
        $attr = [
            'name' => $request->name,
            'dialog_location' => $request->dialog_location ?? null,
            'scope_work' => $request->scope_work ?? null,
            'characteristic' => $request->characteristic ?? null,
            'company_name' => $request->company_name ?? null,
            'site' => $request->site ?? null,
            // 'link_socialnetwork' => $request->link_socialnetwork ?? null,
            'contact_info' => $request->contact_info ?? null,
            'birthday' => $request->birthday ?? null,
        ];
        $client = Client::on()->create($attr);

        $socialnetrowks = json_decode($request->socialnetwork_info, TRUE);

        if(count($socialnetrowks) > 0){
            $attr = [];
            foreach($socialnetrowks as $item){
                $attr[] = [
                    'client_id' => $client->id,
                    'social_network_id' => $item['socialnetrowk_id'],
                    'description' => $item['link'],
                ];
            }
            CrossClientSocialNetwork::on()->insert($attr);
        }

        return redirect()->back();

    }

    public function show($id)
    {

    }

    public function edit($client)
    {
        $clients = Client::on()
            ->find($client)
            ->toArray();
        $socialNetwork = SocialNetwork::on()
            ->get()
            ->toArray();

        $crossSocialNetwork = CrossClientSocialNetwork::on()->get();

        return view('client.client_edit', [
            'clients' => $clients,
            'socialNetwork' => $socialNetwork,

        ]);
    }

    public function update(Request $request, $client)
    {
        $attr = [
            'name' => $request->name ?? null,
            'scope_work' => $request->scope_work ?? null,
            'company_name' => $request->company_name ?? null,
            'site' => $request->site ?? null,
            'link_socialnetwork' => $request->link_socialnetwork ?? null,
            'contact_info' => $request->contact_info ?? null,
            'characteristic' => $request->characteristic ?? null,

        ];

        Client::on()->where('id', $client)->update($attr);
        return redirect()->back()->with(['success' => 'Данные успешно обновлены.']);

    }

    public function destroy($client)
    {
        Client::on()->where('id', $client)->delete();
        return redirect()->back()->with(['success' => 'Заказчик успешно удален']);
    }

    private function filter($request, &$clients){
        $clients->when(!empty($request->name), function ($where) use ($request) {
            $where->where('name', $request->name);
        });
    }
}
