<?php

namespace Lib;
use \Curl\Curl;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
class MyLib
{
    public static function println($msg)
    {
        echo $msg . PHP_EOL;
    }

    public static function  export_csv($result,$fileNamecsv){
        $fileName =$fileNamecsv;
        $output = fopen($fileName, 'w');
        fputcsv($output, array('dateTime', 'ShopName','address','fanpage','phone','website'));
        foreach($result as $row){
            $row = str_replace(["Địa chỉ:", "Fanpage:", "Điện thoại:", "Số điện thoại liên lạc:", "Hotline:", "Website:", "Facebook:", "SĐT"], '', $row);
            fputcsv($output,$row);
        }
        fclose($output);
    }

    public static function get_data($link , &$content){
        $curl = new Curl();

        echo 'Start craw: ' .$link.PHP_EOL;

        $curl->setTimeout(60);
        $curl->setConnectTimeout(60);

        $curl->get($link);

        $error = $curl->error;

        if(!$error){
            $content = $curl->response;
            echo 'End craw: ' .$link.' Sucess !!!'.PHP_EOL;
        }else{
            echo 'End craw: ' .$link.' Failt !!!'.PHP_EOL;
        }

        $curl->close();

        return !$error;
    }

    public static function exporeExcel($result, $page, $fileName)
    {
        $objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
        $objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'DateTime');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'ShopName');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'Address');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'Fanpage');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'Phone');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'Website');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'UrlGet');

        foreach ($result as $key => $row) {
            $rowCount++;
            $row = str_replace(["Địa chỉ:", "Fanpage:", "Điện thoại:", "Số điện thoại liên lạc:", "Hotline:", "Website:", "Facebook:", "SĐT"], '', $row);
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['dateTime']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['ShopName']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['address']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['fanpage']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['phone']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['website']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['url']);
        }

        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $nameFileExcel =  $fileName . '-page-' . $page . '.xlsx';
        $objWriter->save('../output/'.$nameFileExcel);

        // Echo done
        echo  " Done writing file.\r\n";
    }

    public static function exporeExcelTrangVang($result, $page, $fileName)
    {

        $objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
        $objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $rowCount = 1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, 'name_company');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'address');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'phone');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'email');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, 'website');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, 'linhvuc');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, 'linhvuccon');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, 'nganh');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, 'hoTen');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, 'chucvu');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, 'dienthoai');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, 'emailCaNhan');
                var_dump($result);
        foreach ($result as $key => $row) {
            $rowCount++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row['name_company']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row['address']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row['phone']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row['email']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row['website']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row['linhvuc']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row['linhvuccon']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $row['nganh']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $row['hoTen']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $row['chucvu']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $row['dienthoai']);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $row['emailCaNhan']);
        }

        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //$nameFileExcel =  "trangvang" . '-page-' . $page . '.xlsx';
        $objWriter->save('../output/trangvang/' . str_replace('"', '', $fileName) . "_page_" . $page . ".xlsx");

        // Echo done
        echo  " Done writing file.\r\n";
    }




    //menu select trang vang
    public static function menuTrangVang(){
        echo "
        ============= select category ==================
        
            (0)-An ninh, An toàn & Bảo vệ
    
            (1)-Bao bì
            
            (2)-Bảo hộ lao động
            
            (3)-Niên Giám Cao Su
            
            (4)-Cơ khí - Niên Giám Cơ Khí
            
            (5)-Niên Giám Công Nghiệp
            
            (6)-Điện
            
            (7)-Điện Lạnh
            
            (8)-Đồ gia dụng
            
            (9)-Doanh nghiệp Cần Dùng
            
            (10)-Du lịch & Khách sạn
            
            (11)-Giao nhận & Vận chuyển
            
            (12)-Giấy - Ngành Giấy
            
            (13)-Gỗ & Đồ Gỗ
            
            (14)-Hóa Chất
            
            (15)-In ấn & Thiết kế
            
            (16)-May mặc & phụ liệu
            
            (17)-Máy Móc
            
            (18)-Môi Trường
            
            (19)-Nhựa
            
            (20)-Nội Thất & Ngoại Thất
            
            (21)-Nông nghiệp
            
            (22)-Ô tô & Xe máy
            
            (23)-Quà Tặng
            
            (24)-Quảng cáo & truyền thông
            
            (25)-Sức khỏe & Thiết bị y tế
            
            (26)-Thép & Inox
            
            (27)-Thủ công mỹ nghệ
            
            (28)-Thực phẩm & Đồ uống
            
            (29)-Thiết Bị Văn Phòng & VPP
            
            (30)-Vật Liệu Xây Dựng
            
            (31)-Viễn thông
            
            (32)-Xây Dựng
            
            (33)-Xuất Nhập Khẩu
        "."\n";
        echo "Select the category you want to scan :";
        $select_category = fgets(fopen('php://stdin', 'r'));
        do {
            if ($select_category < 0 ){
                echo "Enter the wrong format !!!";
            }
        } while ($select_category < 0 );
        return $select_category;
    }
}