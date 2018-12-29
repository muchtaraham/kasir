<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use App\User;

    class ApiController extends Controller
    {

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
         public function login(){
           if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
               $user = Auth::user();
               $success['token'] =  $user->createToken('App POS')-> accessToken;
               return response()->json(['status' => true,'success' => $success], 200);
           }
           else{
               return response()->json(['status' => false,'error'=>'Unauthorised'], 401);
           }
         }

         public function logout(Request $request)
         {
             $request->user()->token()->revoke();
             return response()->json([
                 'status' => true,
                 'message' => 'Successfully logged out'
             ]);
         }

         public function user(Request $request)
         {
             $res['status'] = true;
             $res['user'] = $request->user();
             return response()->json($res);
         }

        public function menu()
        {
            $menu = DB::table('buku_menus')
            ->join('jenismenus', 'buku_menus.jenis', '=', 'jenismenus.id')
            ->select('buku_menus.id','buku_menus.nama','buku_menus.harga','buku_menus.jenis','buku_menus.stok','jenismenus.nama_jenis as jenis_name')->get();
            $res = array('status' => true, 'menu' => $menu);
            return response()->json($res, 200);
        }

        public function menuready()
        {
            $menu = DB::table('buku_menus')
            ->join('jenismenus', 'buku_menus.jenis', '=', 'jenismenus.id')
            ->where('buku_menus.stok', '=', 1)
            ->select('buku_menus.id','buku_menus.nama','buku_menus.harga','buku_menus.jenis','buku_menus.stok','jenismenus.nama_jenis as jenis_name')->get();
            $res = array('status' => true, 'menu' => $menu);
            return response()->json($res, 200);
        }

        public function menucategory($cat='')
        {
            $menu = DB::table('buku_menus')
            ->join('jenismenus', 'buku_menus.jenis', '=', 'jenismenus.id')
            ->where('buku_menus.stok', '=', 1)
            ->where('buku_menus.jenis', '=', $cat)
            ->select('buku_menus.id','buku_menus.nama','buku_menus.harga','buku_menus.jenis','buku_menus.stok','jenismenus.nama_jenis as jenis_name')->get();
            $res = array('status' => true, 'menu' => $menu);
            return response()->json($res, 200);
        }

        public function categorymenu()
        {
          $categorymenu = DB::table('jenismenus')
          ->select('*')->get();
          $res = array('status' => true, 'category' => $categorymenu);
          return response()->json($res, 200);
        }

        public function pengeluaran()
        {
          $pengeluaran = DB::table('pengeluarans')
          ->select('*')->get();
          $res = array('status' => true, 'pengeluaran' => $pengeluaran);
          return response()->json($res, 200);
        }

        public function pengeluaranbyid($id='')
        {
          $pengeluaran = DB::table('pengeluarans')
          ->select('*')->where('id', '=', $id)->get();
          $res = array('status' => true, 'pengeluaran' => $pengeluaran);
          return response()->json($res, 200);
        }

        public function inputpengeluaran()
        {
          $data = $_POST;
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');
          DB::table('pengeluarans')->insert($data);
          $id = DB::getPdo()->lastInsertId();
          return response()->json(array('status' => true, 'last_insert_id' => $id), 200);
        }

        public function updatepengeluaran()
        {
          $id = $_POST['id'];
          $data = $_POST;
          $data['updated_at'] = date('Y-m-d H:i:s');
          DB::table('pengeluarans')->where('id', $id)->update($data);
          return response()->json(array('status' => true, 'last_update_id' => $id), 200);
        }

        public function deletepengeluaran()
        {
          $id = $_POST['id'];
          $data['updated_at'] = date('Y-m-d H:i:s');
          DB::table('pengeluarans')->where('id', $id)->delete();
          return response()->json(array('status' => true, 'deleted_id' => $id), 200);
        }

        public function order()
        {
          $order = DB::table('orders')
          ->select('*')->where('status', '=', 0)->get();
          $res = array('status' => true, 'order' => $order);
          return response()->json($res, 200);
        }

        public function inputorder()
        {
          $data = $_POST;
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');
          DB::table('orders')->insert($data);
          $id = DB::getPdo()->lastInsertId();
          return response()->json(array('status' => true, 'order_id' => $id), 200);
        }

        public function inputordermenu()
        {
          $data = $_POST;
          $data['created_at'] = date('Y-m-d H:i:s');
          $data['updated_at'] = date('Y-m-d H:i:s');
          DB::table('order_details')->insert($data);
          $id = DB::getPdo()->lastInsertId();
          return response()->json(array('status' => true, 'order_id' => $id), 200);
        }

        // public function orderdetail($id='')
        // {
        //   $order = DB::table('orders')
        //   ->select('*')->where('id', '=', $id)->limit(1)->get();
        //
        //   $menu = DB::table('order_details','buku_menus')
        //   ->select('*')->where('order_details.order_id', '=', $id)->where('order_details.status', '=', 0)->where('order_details.menu_id', '=', 'buku_menus.id')->get();
        //   $res = array('status' => true, 'order' => $order, 'menu' => $menu);
        //   return response()->json($res, 200);
        // }



    }
