<?php
if (!defined('_CODE')) {
    die();
}

function query($sql, $data = [], $check = false) {
    global $conn;
    $ketqua = false;
   
    try {
        $statement = $conn->prepare($sql);

        if (!empty($data)) {
            $ketqua = $statement->execute($data);
        } else {
            $ketqua = $statement->execute();
        }
    } catch (Exception $exp) {
        echo $exp->getMessage() . '<br>';
        echo 'File: ' . $exp->getFile() . '<br>';
        echo 'Line: ' . $exp->getLine();
        die();
    }

    if($check){
        return $statement;
    }

    return $ketqua;
}

// Hàm insert vào data
function insert($table, $data, $options = []) {
    $key = array_keys($data);
    $truong = implode(',', $key);
    $valuetb = ':' . implode(',:', $key);

    $sql = 'INSERT INTO ' . $table . ' (' . $truong . ') VALUES (' . $valuetb . ')';

    $kq = query($sql, $data, $options);
    return $kq;
}



//Hàm update data

function update($table, $data, $condition= ''){
    $update = '';
    foreach($data as $key => $value ){
        $update .= $key .'= :' . $key . ',';
    }
    $update = trim($update, ',');   

    if(!empty($condition)){
        $sql = 'UPDATE ' . $table . ' SET ' .$update . ' WHERE ' . $condition;
    }
    else{
        $sql = 'UPDATE ' . $table . ' SET ' .$update ;
    }
    $kq = query($sql, $data, $condition);
    return $kq;
}

//Hàm delete

function delete($table, $condition, $params = []) {
    if (empty($condition)) {
        $sql = 'DELETE FROM ' . $table;
    } else {
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
    }
    $kq = query($sql, $params, false);  // Truyền mảng $params vào đây
    return $kq;
}


//Lấy nhiều dòng dữ liệu

function getRow($sql){
    $kq = query($sql, '', true);
    if(is_object($kq)){
        $dataFetch = $kq->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($dataFetch)) {
            return $dataFetch; // Trả về dữ liệu nếu có
        }
    }
    return []; // Trả về mảng rỗng nếu không có kết quả hoặc nếu $kq không phải là đối tượng
}


//Lấy một dòng dữ liệu

function oneRow($sql){
    $kq = query($sql, '', true);
    if(is_object($kq)){
     $dataFetch = $kq -> fetch(PDO::FETCH_ASSOC);
    }
     return $dataFetch;
}

//Đếm số dòng dữ kiệu
function getRows($sql){
    $kq = query($sql, '', true);
    if(!empty($kq)){
        return $kq -> rowCount();
    }
}