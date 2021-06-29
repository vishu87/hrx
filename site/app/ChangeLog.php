<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{

    protected $table = 'change_logs';

    public static function addEntry($entity_id, $entity_type, $field_name, $field_tag, $old_value, $new_value, $user_id){
    	$change_log = new ChangeLog;
    	$change_log->entity_id = $entity_id;
    	$change_log->entity_type = $entity_type;
    	$change_log->field_name = $field_name;
    	$change_log->field_tag = $field_tag;
    	$change_log->old_value = $old_value;
    	$change_log->new_value = $new_value;
    	$change_log->created_by = $user_id;
    	$change_log->save();
    }

    public static function listing($id, $type){
        return ChangeLog::select("change_logs.field_tag","change_logs.old_value","change_logs.new_value","users.name as user_name")->join("users","users.id","=","change_logs.created_by")->where("change_logs.entity_id",$id)->where("change_logs.entity_type",$type);
    }
}

