<?php

class FormRepository extends DbRepository{
    public function getFormModel(){
        //フォームの入力データモデル配列
        $data = [
            "name"       => "",
            "age"        => "",
            "prefecture" => "",
            "address1"   => "",
            "address2"   => "",
            "comment"    => "",
        ];
        return $data;
    }

    public function insert($form) {
        //プリペアステートメント用のSQL作成
        $sql = "
            INSERT INTO form(name, age, prefecture, address1, address2, comment)
                VALUES(:name, :age, :prefecture, :address1, :address2, :comment)
        ";
        //SQL実行
        $stmt = $this->execute($sql, $form);
    }
}
