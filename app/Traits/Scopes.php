<?php

namespace App\Traits;

use App\Models\Import\Import_csv_log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use function is_array;
use function is_null;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

trait Scopes
{

    /** Below method is used for perform sorting for any field from any tables in laravel.
     *
     * @param $query - Query get from controller
     * @param $sort - requested $sort value (e.g. first_name or mobile_number)
     * @param $order_by - requested $order_by value (e.g. asc or desc)
     *
     * @param $tablename - actual table name (e.g. users)
     * @param $export_select
     * @return mixed
     */
    public function scopeWithOrderBy($query, $sort, $order_by, $tablename, $export_select, $groupBy = null)
    {
        if (is_null($tablename)) // if $tablename is null
            $tablename = $this->getTable(); // get tablename from model

        if ((is_null($sort) || is_null($order_by)) && is_null($groupBy)) { // if sort & order_by is null
            $sort = config('constants.default_sort_field');
            $order_by = 'desc';
        }

        if (!is_null($this->sortable) && in_array($sort, $this->sortable)) { // if sortable is not null property for respected model is exists requested sort field.
            return $query->orderBy($sort, $order_by); // defined order by clause.
        }

        $foreignSortable = $this->foreign_sortable;
        $isTrue = -1;

        if (!is_null($foreignSortable)) {
            foreach ($foreignSortable as $key => $foreignKey) {
                if (is_array($foreignKey)) {
                    $lastForeignKey = $foreignKey[count($foreignKey) - 1];
                    if ($sort == $lastForeignKey)
                        $isTrue = $key;
                } else {
                    if ($sort == $foreignKey)
                        $isTrue = $key;
                }
            }
        }

        //if foreign_sortable is not null property for respected model is exists requested sort field & foreign_table & foreign_key is not null
        if (!is_null($this->foreign_sortable) && !is_null($this->foreign_table) && !is_null($this->foreign_key)) {
            for ($i = 0; $i < count($this->foreign_sortable); $i++) {
                if ($isTrue == $i) {
                    if (!is_null($this->pivot_table))
                        $query->join($this->foreign_table[$i], $this->pivot_table[$i] . '.' . $this->foreign_sortable[$i], '=', $this->foreign_table[$i] . '.id');
                    else {
                        if (is_array($this->foreign_sortable[$i]) && is_array($this->foreign_table[$i])) {
                            $foreignSortables = $this->foreign_sortable[$i];
                            $foreignTables = $this->foreign_table[$i];
                            for ($j = 0; $j < count($foreignSortables); $j++) {
                                if ($j == 0)
                                    $query->join($foreignTables[$j], $tablename . '.' . $foreignSortables[$j], '=', $foreignTables[$j] . '.id');
                                else
                                    $query->join($foreignTables[$j], $foreignTables[$j - 1] . '.' . $foreignSortables[$j], '=', $foreignTables[$j] . '.id');
                            }
                        } else
                            $query->join($this->foreign_table[$i], $tablename . '.' . $this->foreign_sortable[$i], '=', $this->foreign_table[$i] . '.id');
                    }
                    if (!$export_select) {
                        $query->select($tablename . '.*');
                    }
                    if (is_array($this->foreign_key[$i])) {
                        $foreignKeys = $this->foreign_key[$i];
                        $foreignTables = $this->foreign_table[$i];
                        for ($j = 0; $j < count($foreignKeys); $j++) {
                            if (is_array($foreignTables))
                                $query->orderBy($foreignTables[count($foreignTables) - 1] . '.' . $foreignKeys[$j], $order_by); // defined foreign table order by clause.
                            else
                                $query->orderBy($foreignTables . '.' . $foreignKeys[$j], $order_by); // defined foreign table order by clause.
                        }
                    } else
                        $query->orderBy($this->foreign_table[$i] . '.' . $this->foreign_key[$i], $order_by); // defined foreign table order by clause.
                }
            }
            if ($isTrue != -1)
                return $query;
        }

        $typeSortable = $this->type_sortable;
        $isTrue = -1;

        if (!is_null($typeSortable)) {
            foreach ($typeSortable as $key => $typeKey) {
                if (is_array($typeKey)) {
                    $lastTypeKey = $typeKey[count($typeKey) - 1];
                    if ($sort == $lastTypeKey)
                        $isTrue = $key;
                } else {
                    if ($sort == $typeKey)
                        $isTrue = $key;
                }
            }
        }


        //if type_sortable is not null property for respected model is exists requested sort field & type_enum & type_enum_text is not null
        if (!is_null($this->type_sortable) && !is_null($this->type_enum) && !is_null($this->type_enum_text)) {
            for ($j = 0; $j < count($this->type_sortable); $j++) { // for loop type_sortable property array.
                if ($isTrue == $j) {
                    $logic = '';
                    $typeSortable = $this->type_sortable[$j];
                    for ($k = 0; $k < count($this->type_enum[$j]); $k++) { // for loop type_enum array of array
                        if (is_array($typeSortable))
                            $sort = $typeSortable[count($typeSortable) - 1];
                        $logic .= 'WHEN ' . $sort . ' = "' . config($this->type_enum[$j][$k]) . '" THEN "' . config($this->type_enum_text[$j][$k]) . '"'; // generate DB:raw query
                    }
                    $textValue = 'laravel';
                    if (!$export_select) {
                        $query->select($tablename . '.*', DB::raw('(CASE ' . $logic . ' ELSE "" END) AS ' . $textValue . '_text'));
                    } else {
                        $query->addSelect(DB::raw('(CASE ' . $logic . ' ELSE "" END) AS ' . $textValue . '_text'));
                    }
                    if (is_array($typeSortable)) {
                        for ($k = 0; $k < count($typeSortable); $k += 4) {
                            if ($k == 0)
                                $query->join($typeSortable[$k], $tablename . '.' . $typeSortable[$k + 1], '=', $typeSortable[$k] . '.id');
                            else
                                $query->join($typeSortable[$k], $typeSortable[$k - 4] . '.' . $typeSortable[$k + 1], '=', $typeSortable[$k] . '.id');
                        }
                    }
                    $query->orderBy($textValue . '_text', $order_by); // perform order by
                }
            }
        }
        if ($isTrue != -1)
            return $query;
    }

    /** Below method is used for perform searching for any field from any tables in laravel.
     *
     * @param $query - Query get from controller
     * @param $this - model object variable ( e.g. $this->user got from [ $user = new User() ] )
     * @param $search - search keyword
     *
     * @return mixed
     */
    public function scopeWithSearch($query, $search, $export_select = false)
    {
        if (is_null($search)) { // if $search is null
            return $query;
        }
        $searches = $this->sortable; // Get model $sortable property - When defined on which field search is apply.
        if (!is_null($searches)) {
            $query->where(function ($query) use ($search, $searches) {
                //defined multiple fields search is apply.
                foreach ($searches as $find) {
                    $query->orWhere($find, 'LIKE', "%$search%");
                }
            });
        }
        $foreign_keys = $this->foreign_key; // Get model $foreign_key property - It is foreign table field name which searching is applied on it. (e.g. User model roles table role field apply on search).
        $foreign_methods = $this->foreign_method; // Get model $foreign_method property - It is model method which is defined for relationship between model. (e.g. user model role() method).
        if (!is_null($foreign_keys) && !is_null($foreign_methods)) {
            $where = 'where';
            if (!is_null($searches))
                $where = 'orWhere';
            $query->$where(function ($query) use ($search, $foreign_keys, $foreign_methods) {
                for ($i = 0; $i < count($foreign_keys); $i++) {
                    //defined multiple foreign key fields search is apply.
                    $query->orWhereHas($foreign_methods[$i], function ($query) use ($search, $foreign_keys, $i) {
                        if (is_array($foreign_keys[$i]))
                            $query->where(DB::raw('CONCAT(' . implode('," ",', $foreign_keys[$i]) . ')'), 'LIKE', "%$search%");
                        else
                            $query->where($foreign_keys[$i], 'LIKE', "%$search%");
                    });
                }
            });
        }

        $combineds = $this->combined; // Get model $combined property - It is array of fields which is concate and apply search on it. ( e.g. User model title+first_name+last_name (Mr Chirag Parmar)). Search perform on this combined string.
        if (!is_null($combineds)) {

            $where = 'where';
            if (!is_null($searches) || (!is_null($foreign_keys) && !is_null($foreign_methods)))
                $where = 'orWhere';
            $query->$where(function ($query) use ($search, $combineds) {
                foreach ($combineds as $combined) {
                    //defined search on combined field.
                    $query->orWhere(DB::raw('CONCAT(' . implode('," ",', $combined) . ')'), 'LIKE', "%$search%");
                }
            });
        }

        $type_sortables = $this->type_sortable; // Get model $type_sortable property - It is enum type field. (e.g. M - Male, F - Female).
        $type_enums = $this->type_enum; // Get model $type_enum property - (parse enum value e.g. '0','1' ... and so on).
        $type_enum_texts = $this->type_enum_text; // Get model $type_enum_text - (parse enum text value e.g. 'Male', 'Female' ... and so on).

        //if (!is_null($type_sortables) && !is_null($type_enums) && !is_null($type_enum_texts) && !$export_select) {
        if (!is_null($type_sortables) && !is_null($type_enums) && !is_null($type_enum_texts)) {
            for ($j = 0; $j < count($type_sortables); $j++) {
                $logic = '';
                for ($k = 0; $k < count($type_enums[$j]); $k++) {
                    if (is_array($type_sortables[$j]))
                        $logic .= 'WHEN ' . $type_sortables[$j][count($type_sortables[$j]) - 1] . ' = "' . config($type_enums[$j][$k]) . '" THEN "' . config($type_enum_texts[$j][$k]) . '"';
                    else
                        $logic .= 'WHEN ' . $type_sortables[$j] . ' = "' . config($type_enums[$j][$k]) . '" THEN "' . config($type_enum_texts[$j][$k]) . '"'; //defined case when condition for enum type and it's text.
                }
                $type_table = $this->type_table;
                if (!is_null($type_table)) {
                    $query = $query->select($type_table . '.*', DB::raw('(CASE ' . $logic . ' ELSE "" END)'))
                        ->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
                } else {
                    if (!$export_select)
                        $query = $query->select('*', DB::raw('(CASE ' . $logic . ' ELSE "" END)'))
                            ->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
                    else
                        $query = $query->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
                }
                if (is_array($type_sortables[$j])) {
                    $tablename = $this->getTable();
                    $typeSortable = $type_sortables[$j];
                    for ($k = 0; $k < count($typeSortable); $k += 4) {
                        if ($k == 0)
                            $query->join($typeSortable[$k], $tablename . '.' . $typeSortable[$k + 1], '=', $typeSortable[$k] . '.id');
                        else
                            $query->join($typeSortable[$k], $typeSortable[$k - 4] . '.' . $typeSortable[$k + 1], '=', $typeSortable[$k] . '.id');
                    }
                }
                $query = $query->orWhere(DB::raw('(CASE ' . $logic . ' ELSE "" END)'), 'LIKE', "%$search%");
            }
        }
        //dd($query->toSql());
        return $query;
    }

    /**
     * @param $query - Query get from controller
     * @param $no_pages - parse number of pages
     * @param bool $export_select // true if need paginated data else false
     * @return mixed
     */
    public function scopeWithPerPage($query, $no_pages, $export_select = false)
    {
        if ($export_select) {
            return $query->paginate($query->count());
        } else {
            if (is_null($no_pages)) {
                return $query->paginate(config('constants.paginate'));
            } else {
                return $query->paginate($no_pages);
            }
        }
    }

    public function scopeCodeGenerator($query, $id, $length, $prefix = "")
    {
        $loop_length = ($length - strlen($id));
        $code_zeros = $prefix;
        for ($i = 0; $i < $loop_length; $i++)
            $code_zeros .= '0';
        return ($code_zeros . $id);
    }


    /**
     * This function will perform filter function.
     * @param $query - Query get from controller
     * @param $filters - filter records
     * @return mixed
     */
    public function scopeWithFilter($query, $filters, $request = null)
    {
        // get filters from request in JSON format.
        $filters = json_decode(urldecode(base64_decode($filters)));
        //        $filters = json_decode($filters);

        // Apply filter if it is not null
        if (!is_null($filters)) {

            $query->where(function ($query) use ($filters, $request) {
                // Apply filter for each values
                foreach ($filters as $filterColumn => $filterValue) {
                    if ($filterColumn == "cf") {

                        $query->where(function ($query) use ($filterValue) {

                            if ($filterValue !== "") {
                                foreach ($filterValue as $key => $value) {

                                    foreach ($value as $val) {

                                        foreach ($val as $k => $v) {
                                            $query->where($key, $k, $v);
                                        }
                                    }
                                }
                            }
                        });
                    } else if ($filterColumn == "pf") {  //Apply pivot column filter

                        foreach ($filterValue as $key => $value) {

                            $query->whereHas($key, function ($query) use ($value) {
                                $query->whereIn('id', $value);
                            });
                        }
                    } else if ($filterColumn == "ff") {  //Apply foreign table column filter

                        if (is_array($filterValue)) {

                            foreach ($filterValue as $value) {

                                foreach ($value as $key => $val) {

                                    $query->whereHas($key, function ($query) use ($val) {

                                        foreach ($val as $k => $v) {

                                            $query->whereIn($k, $v);
                                        }
                                    });
                                }
                            }
                        }
                    } else if ($filterColumn == "off") {  //Apply operator foreign table column filter

                        if (is_array($filterValue)) {

                            foreach ($filterValue as $value) {

                                foreach ($value as $key => $val) {

                                    $query->whereHas($key, function ($query) use ($val) {

                                        foreach ($val as $k => $v) {

                                            foreach ($v as $innerValue) {

                                                foreach ($innerValue as $inKey => $inVal) {
                                                    $query->where($k, $inKey, $inVal);
                                                }
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    } else if (!is_array($filterValue)) {  //check whether value is date range value
                        $dates = explode("to", $filterValue); //get two dates
                        if (Carbon::createFromFormat('Y-m-d', trim($dates[0])) !== false) {
                            $dates[0] = $dates[0] . ' 00:00:00';
                        }
                        if (Carbon::createFromFormat('Y-m-d', trim($dates[1])) !== false) {
                            $dates[1] = $dates[1] . ' 23:59:59';
                        }
                        $query->whereBetween($filterColumn, $dates); //filter values based on dates.
                    } else if ($filterValue !== '') {
                        $query->whereIn($filterColumn, $filterValue);
                    }
                }
            });
            //dd(\Str::replaceArray('?', $query->getBindings(), $query->toSql()));
            return $query;
        }
        return $query;
    }

    /**
     * This function will check whether date range is valid or not.
     * The format for date range is YYYY-MM-DDtoYYYY-MM-DD.
     * @param $date
     * @return boolean
     */
    function isDateRange($date)
    {
        if (!is_object($date) && preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])to[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date))
            return true;
        else
            return false;
    }

    /**
     * Export Common for the Type enums.
     *
     * @param $query
     * @param string $sort
     * @return \Illuminate\Database\Query\Expression
     */
    public function scopeWithTypeEnum($query, $sort = '')
    {
        $logic = '';
        if (!is_null($this->type_enum) && !is_null($this->type_enum_text)) {
            if ($sort == '') {
                $sort = $this->type_sortable[0];
                $field_name = $this->type_sortable[0];
            } else {
                $field_name = $sort;
            }
            if ($sort == 'attributes_subtext') {
                $field_name = 'attribute';
            }
            $j = array_search($sort, $this->type_sortable);
            for ($k = 0; $k < count($this->type_enum[$j]); $k++) { // for loop type_enum array of array
                $logic .= 'WHEN ' . $field_name . ' = "' . config($this->type_enum[$j][$k]) . '" THEN "' . config($this->type_enum_text[$j][$k]) . '"'; // generate DB:raw query
            }
        }
        $type_text = DB::raw('(CASE ' . $logic . ' ELSE "" END) AS ' . $sort . '_text');
        return $type_text;
    }

    /**
     * Import csv
     * @param $query
     * @param $request
     * @param $model
     * @param $modelType
     * @param $folderName
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeImportBulk($query, $request, $model, $modelType, $folderName)
    {
        if ($request->hasfile('file')) {

            $only_file_name = str_replace(
                ' ',
                '_',
                strtolower(pathinfo(
                    $request->file('file')->getClientOriginalName(),
                    PATHINFO_FILENAME
                ))
            );
            $only_extension = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_EXTENSION);

            Artisan::call('config:cache');
            $filename = $only_file_name . '_' . config('constants.file.name') . '.' . $only_extension;
            $path = Storage::putFileAs('/public/' . $folderName, $request->file('file'), $filename);

            $file_path = $folderName . pathinfo($path, PATHINFO_BASENAME);
            Excel::import($model, $path);
            if (count($model->getErrors()) > 0) {

                $error_json = json_encode($model->getErrors());

                Import_csv_log::create([
                    'file_path' => $file_path,
                    'filename' => $filename,
                    'model_name' => $modelType,
                    'user_id' => $request->user()->id,
                    'status' => config('constants.import_csv_log.status_enum.0'),
                    'error_log' => $error_json
                ]);
                return response()->json(['errors' => $model->getErrors()], config('constants.validation_codes.unprocessable_entity'));
            }

            Import_csv_log::create([
                'file_path' => $file_path,
                'filename' => $filename,
                'model_name' => $modelType,
                'user_id' => $request->user()->id,
                'status' => config('constants.import_csv_log.status_enum.1'),
                'no_of_rows' => $model->getRowCount()
            ]);

            return response()->json(['data' => true]);
        } else {
            return response()->json(['error' => config('constants.messages.file_csv_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * Check specific model code or any field value already exist or not (Both times Create as well as Update method)
     *
     * @param $query - Calling object
     * @param $request - Request object
     * @param null $fieldName - If you specify specific field if not default "code" field is check
     * @return bool - Return true or false boolean value
     *
     */
    public function scopeCodeExist($query, $request, $fieldName = null, $userType)
    {

        $response = $query->where($fieldName, $request->get($fieldName))->where('user_type', $userType)->first();
        if ($response == null) // Check $response variable is NULL
            return true;
        else {
            if (!is_null($request->get('id'))) { // Check request id variable value is NOT NULL
                if ($response->id == $request->get('id')) // Check $response object id field and request id variable is same
                    return true;
            }
        }
        return false;
    }


    /**
     * This common method is used to delete model restriction will be checked it's exist somewhere or not
     *
     * @param $query
     * @param $models
     * @param $commonIdKey
     * @param $commonIdValue
     * @return array
     */
    public function scopeCommonCodeForDeleteModelRestrictions($query, $models, $commonIdKey, $commonIdValue)
    {
        $inUse = [];

        foreach ($models as $model) {
            $res = $this->commonCodeForCheckModelIsUseInOtherPlace($model, $commonIdKey, $commonIdValue);
            if ($res != "")
                $inUse[] = $res;
        }

        return $inUse;
    }

    /**
     *  Send OTP With Email
     *
     * @param $query - Calling object
     * @param $user
     * @return string
     *
     */
    public function scopeSendOTPWithEmail($query, $user)
    {
        Artisan::call('config:cache');
        $otp = config('constants.generate_otp_number.otp_number');
        $data['verification_otp'] = $otp;
        $user->update($data);

        return true;
    }

    /**
     * This is sub-common method for delete restriction for inner level checking code
     *
     * @param $model
     * @param $id
     * @param $value
     * @return string
     */
    public function commonCodeForCheckModelIsUseInOtherPlace($model, $id, $value)
    {
        $response = $model::where($id, $value)->first();
        if ($response) {
            $model_name = new $model;
            return ucfirst(str_replace('_', ' ', $model_name->getTable()));
        }

        return "";
    }

    /**
     * @param $query
     * @param $request
     * @param $template
     * @param $user
     * @param $order
     * @return mixed
     */
    public function scopeDynamicData($query, $request, $template, $user, $order)
    {

        $legend_filter = $this->getLegendFilterArray($user, $order);
        return $this->filterLegends($legend_filter, $template);
    }

    /**
     * @param $query
     * @param $user
     * @param $order
     * @return array
     */
    public function scopegetLegendFilterArray($query, $user, $order)
    {
        $userData = []; // get user data
        if (!is_null($user)) {
            $userData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ];
        }

        $orderData = [];
        if (!is_null($order)) {
            $orderData = [
                'order_id' => $order->id,
                'courier_name' => $order->courier_name,
                'tracking_id' => $order->tracking_id,
                'courier_link' => $order->courier_link,
            ];
        }

        return array_merge($userData, $orderData); // merge all arrays
    }

    /**
     * @param $query
     * @param $legend_filter
     * @param $curr_data
     * @return array|mixed|string|string[]
     */
    public function scopefilterLegends($query, $legend_filter, $curr_data)
    {
        $filter = $curr_data;

        if (!empty($legend_filter)) {
            foreach ($legend_filter as $key => $legends) {
                $filter = str_replace('{{' . $key . '}}', $legends, $filter);
            }
        }

        return $filter;
    }

    /**
     * Generate temporary signed link for asset
     * @param $value
     * @return string
     */
    public static function generateAssetsLink($value)
    {
        $dateTime = now()->addSeconds(config('constants.asset_link_expiry'));

        // URL::forceRootUrl(config('app.url'));
        $downloadLink = URL::temporarySignedRoute(
            'download.file',
            $dateTime,
            ['file' => Crypt::encryptString($value)]
        );

        return $downloadLink;
    }
}
