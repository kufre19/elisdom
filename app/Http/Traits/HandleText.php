<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;

trait HandleText{
    use MakeMessages;

    public $text_intent;

    public function text_index()
    {
        $this->find_text_intent();
        if ($this->text_intent == "greetings") {
            $this->send_greetings_message($this->userphone);
            die;
        }
        

    }


    public function find_text_intent()
    {
        $message = $this->user_message_lowered;

        $greetings = Config::get("text_intentions.greetings");
        $menu = Config::get("text_intentions.menu");
        


        if (in_array($message, $greetings)) {
            $this->text_intent = "greetings";
        } elseif (in_array($message, $menu)) {
            $this->text_intent = "menu";
        }
         elseif (isset($this->user_session_data['active_command'])) {
            if (!empty($this->user_session_data['active_command'])) {
                $this->handle_session_command($message);
            }
        } else {
            $this->text_intent = "others";
        }
    }

}