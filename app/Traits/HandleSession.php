<?php

namespace App\Traits;
use App\Models\Session;


trait HandleSession {

    
    public function start_new_session()
    {
        $data = ["menu_selected"=>0];
        $json = json_encode($data);
        $model = new Session();
        $model->user_id = $this->userphone;
        $model->session_data = $json;
        $model->expired = time() + 3600;
        $model->save();
    }


    public function did_session_expired()
    {
        $model = new Session();
        $fetch = $model->select('expired')->where('user_id', $this->userphone)->first();

        if (!$fetch) {
            $this->user_session_status = 0;
            return $this->start_new_session();
        } elseif ($fetch->expires_in < time()) {
            $this->update_session();
            $this->user_session_status = 2;
            return true;
        } else {
            $this->user_session_status = 1;
        }
    }


    public function update_session($data = null)
    {
        if ($data == null) {
            $data = ["menu_selected"=>0];
            $data = json_encode($data);
        } else {
            $data = json_encode($data);
        }

        $model = new Session();
        $model->where('user_id', $this->userphone)
            ->update([
                'session_data' => $data,
                'expired' => time() + 3600
            ]);
            
        $this->fetch_user_session();
    }

    public function fetch_user_session()
    {
        $model = new Session();
        $fetch = $model->where('user_id', $this->userphone)->first();
        $this->user_session_data = json_decode($fetch->session_data, true);
    }
}