<?php

namespace App;
use DB;
use App\Scheme;

class Portfolio
{

    public static function getUserPortfolioCompanies($client_id,$date){

        if(is_array($client_id)){
            $client_id = implode(',',$client_id);
            $client_id = "(".$client_id.")";
            $companies1 = DB::select("SELECT distinct( companies.com_id), companies.com_name, companies.com_bse_code, companies.com_isin , user_voting_company.add_date, null as delete_date from user_voting_company inner join companies on user_voting_company.com_id = companies.com_id where user_id in $client_id and user_voting_company.add_date <= '$date'  UNION SELECT distinct( companies.com_id), companies.com_name, companies.com_bse_code, companies.com_isin, user_voting_company_delete.add_date, user_voting_company_delete.delete_date from user_voting_company_delete inner join companies on user_voting_company_delete.com_id = companies.com_id where user_id in $client_id and user_voting_company_delete.add_date <= '$date' and user_voting_company_delete.delete_date > '$date' ");

            $companies2 = DB::select("SELECT distinct( temp_companies.temp_com_id) as com_id, temp_companies.com_isin as com_name, null as com_bse_code, temp_companies.com_isin , user_voting_company.add_date, null as delete_date from user_voting_company inner join temp_companies on user_voting_company.com_id = temp_companies.temp_com_id where user_id in $client_id and user_voting_company.add_date <= '$date'  UNION SELECT distinct( temp_companies.temp_com_id) as com_id, temp_companies.com_isin as com_name, null as com_bse_code, temp_companies.com_isin, user_voting_company_delete.add_date, user_voting_company_delete.delete_date from user_voting_company_delete inner join temp_companies on user_voting_company_delete.com_id = temp_companies.temp_com_id where user_id in $client_id and user_voting_company_delete.add_date <= '$date' and user_voting_company_delete.delete_date > '$date' ");

        } else {
            $companies1 = DB::select("SELECT distinct( companies.com_id), companies.com_name, companies.com_bse_code, companies.com_isin , user_voting_company.add_date, null as delete_date from user_voting_company inner join companies on user_voting_company.com_id = companies.com_id where user_id = $client_id and user_voting_company.add_date <= '$date'  UNION SELECT distinct( companies.com_id), companies.com_name, companies.com_bse_code, companies.com_isin, user_voting_company_delete.add_date, user_voting_company_delete.delete_date from user_voting_company_delete inner join companies on user_voting_company_delete.com_id = companies.com_id where user_id = $client_id and user_voting_company_delete.add_date <= '$date' and user_voting_company_delete.delete_date > '$date' ");

            $companies2 = DB::select("SELECT distinct( temp_companies.temp_com_id) as com_id, temp_companies.com_isin as com_name, null as com_bse_code, temp_companies.com_isin , user_voting_company.add_date, null as delete_date from user_voting_company inner join temp_companies on user_voting_company.com_id = temp_companies.temp_com_id where user_id = $client_id and user_voting_company.add_date <= '$date'  UNION SELECT distinct( temp_companies.temp_com_id) as com_id, temp_companies.com_isin as com_name, null as com_bse_code, temp_companies.com_isin, user_voting_company_delete.add_date, user_voting_company_delete.delete_date from user_voting_company_delete inner join temp_companies on user_voting_company_delete.com_id = temp_companies.temp_com_id where user_id = $client_id and user_voting_company_delete.add_date <= '$date' and user_voting_company_delete.delete_date > '$date' ");
        }

        $companies = $companies1 + $companies2;

        return $companies;
        
    }

    public static function addCompanyInPortfolio($user_id, $com_id, $date){ // date in Y-m-d

        $check = DB::table("user_voting_company")->where("user_id",$user_id)->where("com_id",$com_id)->first();

        if($check){

            $count = 0;
            
            if($check->add_date > $date){

                // if company is in portfolio but in case of old portfolio update by the client the company should be in portfolio prior to the current portfolio date

                // updating add_date in this case
                DB::table("user_voting_company")->where("user_id",$user_id)->where("com_id",$com_id)->update(array(
                    "add_date" => $date
                ));

                // updating meetings in this case
                $count = Portfolio::addFutureMeeting($com_id,$user_id,$date);

                return 'added - '.$count.' - PA';
            }

            return 'Duplicate Entry';

        } else {
            
            DB::table("user_voting_company")->insert(array(
                "user_id" => $user_id,
                "com_id" => $com_id,
                "add_date" => $date
            ));

            $count = 0;

            $count = Portfolio::addFutureMeeting($com_id,$user_id,$date);

            return 'added - '.$count.' - PA';
        }
    }

    public static function removeCompanyFromPortfolio($user_id, $com_id, $date){ // date in Y-m-d

        $remarks = "NA";

        $check = DB::table("user_voting_company")->where("user_id",$user_id)->where("com_id",$com_id)->first();

        if($check){
            DB::table("user_voting_company_delete")->insert(array(
                "user_id" => $user_id,
                "com_id" => $com_id,
                "add_date" => $check->add_date,
                "delete_date" => $date
            ));

            DB::select("DELETE user_voting_proxy_reports from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id=proxy_ad.id where user_voting_proxy_reports.user_id=$user_id and proxy_ad.com_id=$com_id and proxy_ad.record_date >= '$date' ");

            DB::table("user_voting_company")->where("id",$check->id)->delete();

            $remarks = "Deleted";
        } else {

            $check_newer_deleted = DB::table("user_voting_company_delete")->where("user_id",$user_id)->where("com_id",$com_id)->where("add_date","<",$date)->where("delete_date",">",$date)->first();

            if($check_newer_deleted){

                DB::table("user_voting_company_delete")->where("id",$check_newer_deleted->id)->update(array(
                    "delete_date" => $date
                ));

                DB::select("DELETE user_voting_proxy_reports from user_voting_proxy_reports inner join proxy_ad on user_voting_proxy_reports.report_id=proxy_ad.id where user_voting_proxy_reports.user_id=$user_id and proxy_ad.com_id=$com_id and proxy_ad.record_date >= '$date' ");

                $remarks = "Deleted with new delete date";

            } else {
                $remarks = "None deleted";
            }
                        
        }

        return $remarks;
    }


}

