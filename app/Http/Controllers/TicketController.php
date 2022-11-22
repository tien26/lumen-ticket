<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = $request->paginate;
        $number = substr($paginate, 0, 1);

        $hasil = $paginate % 10;
        if ($hasil < 6) {
            $value = $number . 0;
        } else {
            $value = ($number + 1) . 0;
        }

        $data = Ticket::with('status_ticket')->paginate($value);

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'msg' => 404,
                'data' => 'not found',
            ]);
        }

        foreach ($data as $key) {
            $output[] = [
                'id' => $key->id,
                'ticket_title' => $key->ticket_title,
                'user_id' => $key->user_id,
                'ticket_msg' => $key->ticket_msg,
                'status' => $key->status_ticket->status
            ];
        }

        return response()->json([
            'status' => true,
            'msg' => 200,
            'page_size' => (int)$value,
            'data' => $output,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        // $name = $request->filter['filter_name'];
        $type = $request->filter['filter_type'];
        $paginate = $request->page_size;
        $number = substr($paginate, 0, 1);

        $hasil = $paginate % 10;
        if ($hasil < 6) {
            $value = $number . 0;
        } else {
            $value = ($number + 1) . 0;
        }

        if ($type == "between") {
            $first = date('Y-m-d', strtotime($request->filter['filter_value_first']));
            $last = date('Y-m-d', strtotime($request->filter['filter_value_last']));
            $data = Ticket::with('status_ticket')->whereBetween('created_at', [$first, $last])->paginate($value);
        } else if ($type == 'before') {
            $first = date('Y-m-d', strtotime($request->filter['filter_value']));
            $data = Ticket::with('status_ticket')->where('created_at', '<', $first)->paginate($value);
        } else if ($type == 'after') {
            $first = date('Y-m-d', strtotime($request->filter['filter_value']));
            $data = Ticket::with('status_ticket')->where('created_at', '>', $first)->paginate($value);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 404,
                'data' => 'not found',
            ]);
        }

        foreach ($data as $key) {
            $output[] = [
                'ticket_title' => $key->ticket_title,
                'status' => $key->status_ticket->status,
                'created_at' => $key->created_at,
                'user_id' => $key->user_id,
            ];
        }

        return response()->json([
            'status' => true,
            'msg' => 200,
            'page_size' => (int)$value,
            'data' => $output,
        ]);
    }

    public function filterSort(Request $request)
    {
        // $name = $request->filter['filter_name'];
        $type = $request->filter['filter_type'];
        $dir = $request->sort['sort_dir'];

        $paginate = $request->page_size;
        $number = substr($paginate, 0, 1);

        $hasil = $paginate % 10;
        if ($hasil < 6) {
            $value = $number . 0;
        } else {
            $value = ($number + 1) . 0;
        }

        if ($type == "between") {
            $first = date('Y-m-d', strtotime($request->filter['filter_value_first']));
            $last = date('Y-m-d', strtotime($request->filter['filter_value_last']));
            $data = Ticket::with('status_ticket')->whereBetween('created_at', [$first, $last])->orderBy('created_at', $dir)->paginate($value);
        } else if ($type == 'before') {
            $first = date('Y-m-d', strtotime($request->filter['filter_value']));
            $data = Ticket::with('status_ticket')->where('created_at', '<', $first)->orderBy('created_at', $dir)->paginate($value);
        } else if ($type == 'after') {
            $first = date('Y-m-d', strtotime($request->filter['filter_value']));
            $data = Ticket::with('status_ticket')->where('created_at', '>', $first)->orderBy('created_at', $dir)->paginate($value);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 404,
                'data' => 'not found',
            ]);
        }

        foreach ($data as $key) {
            $output[] = [
                'ticket_title' => $key->ticket_title,
                'status' => $key->status_ticket->status,
                'created_at' => $key->created_at,
                'user_id' => $key->user_id,
            ];
        }

        return response()->json([
            'status' => true,
            'msg' => 200,
            'page_size' => (int)$value,
            'data' => $output,
        ]);
    }

    public function sort(Request $request)
    {
        $sort_name = $request->sort['sort_name'];
        $dir = $request->sort['sort_dir'];
        $paginate = $request->page_size;
        $number = substr($paginate, 0, 1);

        $hasil = $paginate % 10;
        if ($hasil < 6) {
            $value = $number . 0;
        } else {
            $value = ($number + 1) . 0;
        }

        $data = Ticket::orderBy($sort_name, $dir)->paginate($value);

        foreach ($data as $key) {
            $output[] = [
                'ticket_title' => $key->ticket_title,
                'status' => $key->status_ticket->status,
                'created_at' => $key->created_at,
                'user_id' => $key->user_id,
            ];
        }

        return response()->json([
            'status' => true,
            'msg' => 200,
            'page_size' => (int)$value,
            'data' => $output,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ticket_title' => 'required|min:10|max:100',
            'user_id' => 'required|integer',
            'ticket_msg' => 'required|min:100'
        ]);

        $exist_user = User::where('id', $request->user_id)->get();

        if ($exist_user->isEmpty()) {
            return response()->json([
                'status' => 404,
                'msg' => 'user_id not found',
            ]);
        }
        // user id diambil dari id yang login
        $data = [
            'ticket_title' => $request->ticket_title,
            'user_id' => $request->user_id,
            'ticket_msg' => '<p>' . $request->ticket_msg . '</p>',
            'status' => 1
        ];

        Ticket::create($data);
        return response()->json($data);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Ticket::with('status_ticket')->where('id', $id)->first();

        // dd($data);

        if ($data == null) {
            return response()->json([
                'status' => false,
                'msg' => 404,
                'data' => 'not found',
            ]);
        }
        $output = [
            'id' => $data->id,
            'ticket_title' => $data->ticket_title,
            'user_id' => $data->user_id,
            'ticket_msg' => $data->ticket_msg,
            'status' => $data->status_ticket->status
        ];
        return response()->json([
            'status' => true,
            'msg' => 200,
            'data' => $output,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
