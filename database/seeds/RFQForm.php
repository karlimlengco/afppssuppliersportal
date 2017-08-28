<?php

use Illuminate\Database\Seeder;
use Revlv\Settings\Forms\RFQ\RFQEloquent;

class RFQForm extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pcco =  \Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent::pluck('id')->toArray();

        $inputs = [];
        foreach($pcco as $pc)
        {

            $inputs[]     =   new RFQEloquent([
                "pcco_id" => $pc,
                "content"   => "<li>DELIVERY PERIOD IS ATLEAST SEVEN (7) CALENDAR DAYS AT GHQ HEADQUARTERS</li>
                            <li>WARRANTY SHALL BE FOR THE PERIOD OF THREE (3) MONTHS FOR SUPPLIES & MATERIALS, ONE (1) YEAR FOR EQUIPMENT, FROM DATE OF ACCEPTANCE BY THE PROCURING ENTITY OR PRODUCT WARRANTY WHICHEVER IS LONGER.</li>
                            <li>PRICE VALIDITY SHALL BE FIXED DURING THE BIDDERS PERFORMANCE OF THE CONTRACT AND NOT SUBJECT TO VARIATION OR PRICE ESCALATION ON ANY ACCOUNT.</li>
                            <li>PHILGEPS REGISTRATION SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</li>
                            <li>BIDDERS SHALL SUBMIT ORIGINAL DOCUMENTS SHOWING CERTIFICATIONS OF THE PROJECT BEING OFFERED OR ITS EQUIVALENT, IF NECESSARY.</li>
                            <li>FOR INFRASTRUCTURE PROJECT, INTERESTED PROPONENTS SHOULD SUBMIT CERTIFICATE OF SITE INSPECTION ISSUED BY THE END USER.</li>"
                ]);
        }

        $datas = collect($inputs);

        $datas->each(function($data) {
            $data->save();
        });
    }
}
