<?php

namespace Revlv\Settings\Suppliers;
use DB;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SupplierRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SupplierEloquent::class;
    }

    /**
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function getById($id)
    {
        $model  =    $this->model;

        $model  =   $model->where('id', '=', $id);

        return $model->first();
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function lists($id = 'id', $value = 'name')
    {

        $model =  $this->model;

        $model =  $model->select([
            'suppliers.id',
            // 'suppliers.name',
            DB::raw(" (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'dti' order by supplier_attachments.created_at desc limit 1) as dti_validity_date "),
            DB::raw("(select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'mayors_permit' order by supplier_attachments.created_at desc limit 1) as mayors_permit_validity_date "),
            DB::raw(" (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'tax_clearance' order by supplier_attachments.created_at desc limit 1) as tax_clearance_validity_date "),
            DB::raw(" (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'philgeps_registraion' order by supplier_attachments.created_at desc limit 1) as philgeps_registraion_validity_date "),


            DB::raw("CONCAT(suppliers.name,' ',
                CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'dti' order by supplier_attachments.created_at desc limit 1) IS NULL
                THEN '(failed)'
                ELSE
                    CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'mayors_permit' order by supplier_attachments.created_at desc limit 1) IS NULL
                    THEN '(failed)'
                    ELSE
                        CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'tax_clearance' order by supplier_attachments.created_at desc limit 1) IS NULL
                        THEN '(failed)'
                        ELSE

                            CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'philgeps_registraion' order by supplier_attachments.created_at desc limit 1) IS NULL
                            THEN '(failed)'
                            ELSE ''
                            END
                        END
                    END
                END)
                as name"),


            // DB::raw("CONCAT(suppliers.name, ' ',
            //     IFNULL( (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'dti' and validity_date > CURDATE() order by supplier_attachments.created_at desc limit 1),
            //      '(failed)')
            //      ) as 'Custom Parameters'"),
        ]);

        $model =  $model->where('is_blocked','=',0);
        $model =  $model->where('status','=','accepted');

        return $model->pluck($value, $id)->all();
    }

    public function findAndGetStatus($id)
    {

        $model =  $this->model;

        $model =  $model->select([
            'suppliers.id',
            DB::raw("
                CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'dti' order by supplier_attachments.created_at desc limit 1) IS NULL
                THEN '(failed)'
                ELSE
                    CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'mayors_permit' order by supplier_attachments.created_at desc limit 1) IS NULL
                    THEN '(failed)'
                    ELSE
                        CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'tax_clearance' order by supplier_attachments.created_at desc limit 1) IS NULL
                        THEN '(failed)'
                        ELSE

                            CASE WHEN (select supplier_attachments.validity_date from supplier_attachments left join suppliers as supp on supplier_attachments.supplier_id  = supp.id where supplier_attachments.supplier_id = suppliers.id and supplier_attachments.type = 'philgeps_registraion' order by supplier_attachments.created_at desc limit 1) IS NULL
                            THEN '(failed)'
                            ELSE ''
                            END
                        END
                    END
                END
                as name")
            ]);


        $model =  $model->where('id','=', $id);

        return $model->first();

    }
}
