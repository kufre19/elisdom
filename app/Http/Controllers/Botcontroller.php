<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HandleSession;
use App\Traits\HandleText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Botcontroller extends Controller
{

    // use  HandleText,HandleSession;
    public $user_message_original;
    public $user_message_lowered;
    public $button_id;
    public $menu_item_id; 
    public $username;
    public $userphone;
    public $userfetched;
    public $message_type;
    public $user_session_data;
    public $user_session_status;

    public function __construct(Request $request)
    {
        // $this->username =$request['entry'][0]['changes'][0]["value"]['contacts'][0]['profile']['name'] ?? "there";
        // $this->userphone =$request['entry'][0]['changes'][0]["value"]['contacts'][0]['wa_id'];


        // if(isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['text']))
        // {
        //     $this->user_message_original = $request['entry'][0]['changes'][0]["value"]['messages'][0]['text']['body'];
        //     $this->user_message_lowered  = strtolower($this->user_message_original);
        //     $this->message_type = "text";
        
        // }
        // if(isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['order']))
        // {
        //     $this->wa_cart = $request['entry'][0]['changes'][0]["value"]['messages'][0]['order']['product_items'];
        //     $this->wa_cart_message = $request['entry'][0]['changes'][0]["value"]['messages'][0]['order']['text'] ?? "None";
        //     $this->message_type = "order";


        // }

        // if(isset($request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive']))
        // {
        //     $interactive_type = $request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive']['type'];
        //     switch ($interactive_type) {
        //         case 'list_reply':
        //             $this->menu_item_id = $request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive']['list_reply']['id'];
        //             $this->message_type = "menu";

        //             break;

        //         case 'button_reply':
        //             $this->button_id = $request['entry'][0]['changes'][0]["value"]['messages'][0]['interactive']['button_reply']['id'];
        //             $this->message_type = "button";

        //             break;
                
                
        //         default:
        //             dd("unknow command");
        //             break;
        //     }
           


        // }
       

        $data = json_encode($request->all());
        Storage::disk('public')->put("public/".time().".json",$data);
        die;
        
    }

    public  function register_user()
    {
        $user_model = new User();
        $user_model->name = $this->username;
        $user_model->wa_id = $this->userphone;
        $user_model->save();

    }

   public function fetch_user()
   {
    $user_model = new User();
    $fetch_user = $user_model->where('wa_id',$this->userphone)->first();
    if($fetch_user->count() < 1)
    {
        $this->register_user();
    }

   }

    

    public function index(Request $request)
    {
        $this->fetch_user();
        // $this->did_session_expired();
        switch ($this->message_type) {
            case 'text':
                $this->text_index();
                break;

            case 'button':

                $this->button_index();
                break;

            case 'menu':
                $this->menu_index();
                break;

            case 'order':
                $this->add_wa_cart_items();
                break;
            
            default:
                die;
                break;
        }


    }
    
   
}
