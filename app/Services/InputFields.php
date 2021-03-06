<?php
/**
 * Created by PhpStorm.
 * User: alfju
 * Date: 9/26/2018
 * Time: 09:42
 */

namespace App\Services;


use Illuminate\Support\Facades\Auth;

class InputFields
{
    public static function inputFieldsConfiguration($request){

        if( $request['id']) {
            $fields = [
                'url' => $request['url'],
                'url_security' => $request['url_security'],
                'name' => $request['name'],
                'contact' => $request['contact'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'whatsapp' => $request['whatsapp'],
                'summary' => $request['summary'],
                'about' => $request['about'],
                'zipcode' => $request['zipcode'],
                'address' => $request['address'],
                'district' => $request['district'],
                'number' => $request['number'],
                'state' => $request['state'],
                'city' => $request['city']
            ];
        }else{
            $fields = [
                'url' => $request['url'],
                'url_security' => $request['url_security'],
                'name' => $request['name'],
                'nickname' => str_slug($request['name'], '-'),
                'contact' => $request['contact'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'whatsapp' => $request['whatsapp'],
                'about' => $request['about'],
                'zipcode' => $request['zipcode'],
                'address' => $request['address'],
                'district' => $request['district'],
                'number' => $request['number'],
                'state' => $request['state'],
                'city' => $request['city'],
                'status_id' => $request['status_id']
            ];
        }

        return $fields;
    }

    public static function inputFieldsUser($request)
    {
        if($request['configuration_id']){
            $configuration_id = $request['configuration_id'];
        }else{
            $configuration_id = Auth::user()->configuration_id;
        }

        if($request['password']) {
            $fields = [
                'name'             => $request['name'],
                'email'            => $request['email'],
                'password'         => bcrypt($request['password']),
                'configuration_id' => $configuration_id,
                'type_id'          => $request['type_id'],
                'status_id'        => $request['status_id']
            ];
        }else{
            $fields = [
                'name'             => $request['name'],
                'email'            => $request['email'],
                'configuration_id' => $configuration_id
            ];
            if($request['type_id']){
                $fields['type_id'] = $request['type_id'];
            }
            if($request['status_id']){
                $fields['status_id'] = $request['status_id'];
            }
        }

        return $fields;
    }

    public static function inputFieldsUserSite($request)
    {
        if(Auth::user() && Auth::user()->configuration_id){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = ConfigurationSite::getConfiguration()->id;
        }

        if($request['id']) {
            if ($request['password']) {
                $fields = [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => bcrypt($request['password']),
                    'configuration_id' => $configuration_id,
                    'type_id' => userTypeId('customer'),
                    'status_id' => $request['status_id'] ? $request['status_id'] : statusOrder('inactive')
                ];
            } else {
                $fields = [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'configuration_id' => $configuration_id
                ];
                if ($request['type_id']) {
                    $fields['type_id'] = userTypeId('customer');
                }
                if ($request['status_id']) {
                    $fields['status_id'] = $request['status_id'] ? $request['status_id'] : statusOrder('inactive');
                }
            }
        }else{
            if ($request['password']) {
                $fields = [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => bcrypt($request['password']),
                    'configuration_id' => $configuration_id,
                    'type_id' => userTypeId('customer'),
                    'active_token' => md5(date('Y-m-d H:i:s')),
                    'status_id' => $request['status_id'] ? $request['status_id'] : statusOrder('inactive')
                ];
            } else {
                $fields = [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'active_token' => md5(date('Y-m-d H:i:s')),
                    'configuration_id' => $configuration_id
                ];
                if ($request['type_id']) {
                    $fields['type_id'] = userTypeId('customer');
                }
                if ($request['status_id']) {
                    $fields['status_id'] = $request['status_id'] ? $request['status_id'] : statusOrder('inactive');
                }
            }
        }

        return $fields;
    }

    public static function inputFieldsCategory($request){
        $status = $request['status_id'] ? $request['status_id'] : 2;
        $profile = Auth::user()->type_id;
        if($profile > 1){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = $request['configuration_id'];
        }

        $fields = [
            'deep'                     => 1,
            'name'                     => $request['name'],
            'slug'                     => str_slug($request['name'], '-'),
            'description'              => $request['description'],
            'display_on_menu'          => $request['display_on_menu'],
            'order'                    => $request['order'],
            'status_id'                => $status,
            'configuration_id'         => $configuration_id
        ];

        return $fields;
    }


    public static function inputFieldsSubCategory($request){
        $status = $request['status_id'] ? $request['status_id'] : 2;
        $profile = Auth::user()->type_id;
        if($profile > 1){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = $request['configuration_id'];
        }

        $fields = [
            'deep'               => 2,
            'name'               => $request['name'],
            'slug'               => str_slug($request['name'], '-'),
            'description'        => $request['description'],
            'status_id'          => $status,
            'configuration_id'   => $configuration_id,
            'category_id'        => $request['category_id']
        ];

        return $fields;
    }


    public static function inputFieldsProduct($request){
        $status = $request['status_id'] ? $request['status_id'] : 2;
        $profile = Auth::user()->type_id;
        if($profile > 1){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = $request['configuration_id'];
        }

        $fields = [
            'sku'              => $request['sku'],
            'name'             => $request['name'],
            'slug'             => str_slug($request['name'], '-'),
            'description'      => $request['description'],
            'meta_title'       => $request['meta_title'],
            'meta_description' => $request['meta_description'],
            'meta_keyword'     => $request['meta_keyword'],
            'price'            => moneyReverse($request['price']),
            'qty'              => $request['qty'],
            'weight'           => $request['weight'],
            'height'           => $request['height'],
            'width'            => $request['width'],
            'length'           => $request['length'],
            'packing'          => $request['packing'] ? moneyReverse($request['packing']) : 0.00,
            'configuration_id' => $configuration_id,
            'status_id'        => $status
        ];

        return $fields;
    }


    public static function inputFieldsDocument($request, $extension, $size, $fileName){
        $status = $request['status_id'] ? $request['status_id'] : 5;
        $profile = Auth::user()->type_id;
        if($profile > 1){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = $request['configuration_id'];
        }

        if($extension == null || $size == null || $fileName == null) {
            $fields = [
                'name' => $request['name'],
                'description' => $request['description'],
                'type_id' => $request['type_id'],
                'configuration_id' => $configuration_id,
                'status_id' => $status
            ];
        }else{
            $fields = [
                'name' => $request['name'],
                'description' => $request['description'],
                'type_id' => $request['type_id'],
                'file' => $fileName,
                'extension' => $extension,
                'size' => $size,
                'date' => date('Y:m:d'),
                'time' => date('H:i:s'),
                'configuration_id' => $configuration_id,
                'status_id' => $status
            ];
        }

        return $fields;
    }


    public static function inputFieldsBanner($request, $extension, $size, $fileName){
        $status = $request['status_id'] ? $request['status_id'] : 2;
        $profile = Auth::user()->type_id;
        if($profile > 1){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = $request['configuration_id'];
        }

        if($extension == null || $size == null || $fileName == null) {
            $fields = [
                'name' => $request['name'],
                'description' => $request['description'],
                'link' => $request['link'],
                'configuration_id' => $configuration_id,
                'status_id' => $status
            ];
        }else{
            $fields = [
                'name' => $request['name'],
                'description' => $request['description'],
                'link' => $request['link'],
                'file' => $fileName,
                'extension' => $extension,
                'size' => $size,
                'configuration_id' => $configuration_id,
                'status_id' => $status
            ];
        }

        return $fields;
    }


    public static function inputFieldsOrder($request){
        $status = $request['status_id'] ? $request['status_id'] : statusOrder('proccess');
        $customer_id = $request['customer_id'] ? $request['customer_id'] : null;
        $configuration_id = Auth::user()->configuration_id;

        if($request['type'] == 2) {
            $fields = [
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'about' => $request['about'],
                'description' => $request['description'],
                'configuration_id' => $configuration_id,
                'user_id' => Auth::user()->id,
                'customer_id' => $customer_id,
            ];
        }else {
            $fields = [
                'origin' => quoteOrigin(1),
                'type' => 1,
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'about' => $request['about'],
                'description' => $request['description'],
                'zipcode' => $request['zipcode'],
                'address' => $request['address'],
                'district' => $request['district'],
                'number' => $request['number'],
                'state' => $request['state'],
                'city' => $request['city'],
                'status_id' => $status,
                'configuration_id' => $configuration_id,
                'user_id' => Auth::user()->id,
                'customer_id' => $customer_id,
            ];
        }

        return $fields;
    }

    public static function inputFieldsQuote($request, $customer, $email,  $phone, $company, $total)
    {
        $fields = [
            'origin'           => quoteOrigin(1),
            'type'             => 2,
            'name'             => $customer->name,
            'email'            => $email->email,
            'phone'            => $phone->phone,
            'zipcode'          => $company ? $company->zipcode : null,
            'address'          => $company ? $company->address : null,
            'district'         => $company ? $company->district : null,
            'number'           => $company ? $company->number : null,
            'state'            => $company ? $company->state : null,
            'city'             => $company ? $company->city : null,
            'total'            => $total,
            'discount'         => $request->discount,
            'description'      => $request->description,
            'status_id'        => statusOrder('new'),
            'configuration_id' => Auth::user()->configuration_id,
            'user_id'          => Auth::user()->id,
            'customer_id'      => $request['customer_id'],
        ];

        return $fields;
    }

    public static function inputFieldsOrderAnnotation($request){
        $fields = [
            'user_name'        => $request['user_name'],
            'description'      => $request['description'],
            'color'            => $request['color'],
            'order_id'         => $request['order_id'],
            'user_id'          => $request['user_id'],
            'status_id'        => statusOrder('open')
        ];

        return $fields;
    }

    public static function inputFieldsOrderStore($user, $total){
        $status           =  statusOrder('proccess');
        $customer_id      = $user->id ? $user->id : null;
        $configuration_id = Auth::user()->configuration_id ? Auth::user()->configuration_id : null;

        $fields = [
            'origin'           => quoteOrigin(2),
            'type'             => 1,
            'name'             => $user->name,
            'email'            => $user->email,
            'phone'            => $user->phone,
            'about'            => null,
            'description'      => null,
            'total'            => $total,
            'zipcode'          => $user->zipcode,
            'address'          => $user->address,
            'district'         => $user->district,
            'number'           => $user->number,
            'state'            => $user->state,
            'city'             => $user->city,
            'status_id'        => $status,
            'configuration_id' => $configuration_id,
            'user_id'          => null,
            'customer_id'      => $customer_id,
        ];

        return $fields;
    }


    public static function inputFieldsPage($request,  $fileName){
        $status = $request['status_id'] ? $request['status_id'] : 2;

        if($fileName == null) {
            $fields = [
                'title'            => $request['title'],
                'googlemaps'       => $request['googlemaps'],
                'summary'          => $request['summary'],
                'text'             => $request['text'],
                'type'             => $request['type'],
                'show_form'        => $request['show_form'],
                'configuration_id' => Auth::user()->configuration_id,
                'status_id'        => $status
            ];
        }else{
            $fields = [
                'title'            => $request['title'],
                'googlemaps'       => $request['googlemaps'],
                'summary'          => $request['summary'],
                'text'             => $request['text'],
                'banner'           => $fileName,
                'type'             => $request['type'],
                'show_form'        => $request['show_form'],
                'configuration_id' => Auth::user()->configuration_id,
                'status_id'        => $status
            ];
        }

        return $fields;
    }


    public static function inputFieldsMidia($request, $extension, $size, $fileName){
        $status = $request['status_id'] ? $request['status_id'] : 2;
        $profile = Auth::user()->type_id;
        if($profile > 1){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = $request['configuration_id'];
        }

        if($extension == null || $size == null || $fileName == null) {
            $fields = [
                'name' => $request['name'],
                'description' => $request['description'],
                'configuration_id' => $configuration_id,
                'status_id' => $status
            ];
        }else{
            $fields = [
                'name' => $request['name'],
                'description' => $request['description'],
                'file' => $fileName,
                'extension' => $extension,
                'size' => $size,
                'configuration_id' => $configuration_id,
                'status_id' => $status
            ];
        }

        return $fields;
    }

    public static function inputFieldsSupport($request, $extension, $size, $fileName){
        $profile = Auth::user()->type_id;
        if($profile > 1){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = $request['configuration_id'];
        }

        if($extension == null || $size == null || $fileName == null) {
            $fields = [
                'name'             => $request['name'],
                'title'            => $request['title'],
                'description'      => $request['description'],
                'status_id'        => statusOrder('open'),
                'configuration_id' => $configuration_id,
                'user_id'          => $request['user_id'],
            ];
        }else{
            $fields = [
                'name'             => $request['name'],
                'title'            => $request['title'],
                'description'      => $request['description'],
                'file'             => $fileName,
                'extension'        => $extension,
                'size'             => $size,
                'status_id'        => statusOrder('open'),
                'configuration_id' => $configuration_id,
                'user_id'          => $request['user_id'],
            ];
        }

        return $fields;
    }

    public static function inputFieldsSupportAnswer($request, $extension, $size, $fileName)
    {
        if($extension == null || $size == null || $fileName == null) {
            $fields = [
                'name'             => $request['name'],
                'description'      => $request['description'],
                'support_id'       => $request['support_id'],
                'user_id'          => $request['id'],
            ];
        }else{
            $fields = [
                'name'             => $request['name'],
                'description'      => $request['description'],
                'file'             => $fileName,
                'extension'        => $extension,
                'size'             => $size,
                'support_id'       => $request['support_id'],
                'user_id'          => $request['id'],
            ];
        }

        return $fields;
    }

    public static function inputFieldsContact($request)
    {
        if(Auth::user() && Auth::user()->configuration_id){
            $configuration_id = Auth::user()->configuration_id;
        }else{
            $configuration_id = ConfigurationSite::getConfiguration()->id;
        }

        $fields = [
            'name'             => $request['name'],
            'origin'           => 1,
            'message'          => $request['message'],
            'configuration_id' => $configuration_id,
            'status_id'        => statusOrder('new')
        ];

        return $fields;
    }

    public static function inputFieldsAttachement($request, $extension, $size, $fileName){

        if($extension == null || $size == null || $fileName == null) {
            $fields = [
                'name'        => $request['name'],
                'description' => $request['description'],
                'contact_id'  => $request['id'],
            ];
        }else{
            $fields = [
                'name' => $request['name'],
                'description' => $request['description'],
                'file'        => $fileName,
                'extension'   => $extension,
                'size'        => $size,
                'date'        => date('Y:m:d'),
                'time'        => date('H:i:s'),
                'contact_id'  => $request['id']
            ];
        }

        return $fields;
    }

}