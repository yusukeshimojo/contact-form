<?php

class FormController extends Controller {
    //認証が必要なアクション名の配列
    //今回のアプリでは認証が必要なアクションはない
    protected $auth_actions = [];

    //インデックスアクション
    public function indexAction() {
        /*
         * モデルからフォームで利用する
         * データ項目が格納されたハッシュ配列を取得
         */
        $form = $this->db_manager->get('Form')->getFormModel();

        //セッションから情報を取得
        $session_form = $this->session->get("form");
        //セッション情報があれば、配列同士をマージする
        if(!is_null($session_form)) {
            $form = array_merge($form,$session_form);
        }
        //Viewテンプレートに渡すデータ配列作成
        $data = [
            "form" => $form,
        ];
        return $this->render($data);
    }

    public function confirmAction() {
        //POSTリクエストでなければ404へリダイレクト
        if (!$this->request->isPost()) {
            $this->forward404();
        }
        //モデルデータ取得
        $form = $this->db_manager->get('Form')->getFormModel();
        $keys = array_keys($form);
        //モデルデータに該当するPOST値を取得
        foreach($keys as $key) {
            $form[$key] = $this->request->getPost($key);
        }
        //エラー情報の配列を初期化
        $errors = [];
        //名前の必須チェック
        if(empty($form["name"])){
            $errors[] = "名前は必須です";
        }
        //年齢の必須チェック
        if(empty($form["age"])){
            $errors[] = "年齢は必須です";
            //年齢の数値チェック
        } else if(!is_numeric($form["age"])){
            $errors[] = "年齢は数値を入力してください";
        }
        //エラーがない場合、確認画面を表示
        if (count($errors) === 0) {
            //入力されたデータをセッションに格納
            $this->session->set("form",$form);
            //Viewテンプレートに渡すデータ配列作成
            $data = [
                "form"    => $form,
                "_token"  => $this->generateCsrfToken('form/confirm'),
            ];
            return $this->render($data);
        }
        //Viewテンプレートに渡すデータ配列作成エラー情報が渡される
        $data = [
            "form" => $form,
            "errors" => $errors,
        ];
        //エラーがある場合、入力画面を表示
        return $this->render($data,"index");
    }

    public function completeAction() {
        //POSTリクエストでなければ404へリダイレクト
        if (!$this->request->isPost()) {
            $this->forward404();
        }
        //トークンを取得
        $token = $this->request->getPost('_token');
        //トークンが不正な値であれば入力画面へリダイレクト
        if (!$this->checkCsrfToken('form/confirm', $token)) {
            return $this->redirect('/form/index');
        }

        //セッションからフォームのデータを取得
        $form = $this->session->get("form");

        //テーブルに書き込む
        $this->db_manager->get("Form")->insert($form);

        //セッションデータを削除
        $this->session->clear();

        //Viewテンプレートに渡すデータ配列作成
        $data = [
            "form" => $form
        ];
        //完了画面を表示
        return $this->render($data);
    }
}
