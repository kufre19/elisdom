<?php

namespace App\Http\Controllers;

use App\Traits\HandleText;
use App\Traits\MakeMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UltraController extends Controller
{
    use HandleText, MakeMessages;

    public function index(Request $request)
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data,true);
        $parcel = $data['parcel'];
        $parcel_status = $data['parcel_status_history'];
        $to = str_replace("+","",$parcel['recipient']['phone_number']);
        $invoice_number = $parcel['invoice_number'];
        $status = $parcel['status_label'];
        $status = "Statusi juaj aktual i parcelës është :{$status}";
        $notes ="shënim: ". $parcel['notes'];
        $description ="Lidhja e ndjekjes: ". "https://u-cep.com/tracking?barcode=".$parcel['barcode'];

        $parameters = [
                [
                    "type"=>"text",
                    "text"=>$invoice_number
                ],
                [
                    "type"=>"text",
                    "text"=>$status
                ],
                [
                    "type"=>"text",
                    "text"=>$description
                ],
                [
                    "type"=>"text",
                    "text"=>$notes
                ],
                
        ];
        $message = $this->make_template_message($parameters,$to);
        $this->send_post_curl($message);
    //    dd($message);
        // $data = json_encode($request->all());
        // Storage::disk('public')->put("public/".time().".json",$data);
        // die;
    }
}
