<?php

use DiDom\Document;
use Lib\MyLib;

set_time_limit(0);
require __DIR__ . '/../vendor/autoload.php';
$url = "https://trangvangvietnam.com/?cate=full";
// run
$select_category = MyLib::menuTrangVang();
MyLib::println("Enter the page number :");
$total_number_pages = fgets(fopen('php://stdin', 'r'));

//MyLib::println("Enter file name :");
//$fileName = fgets(fopen('php://stdin', 'r'));
$fileName = "bao-bi_";
for ($i = 1; $i <= $total_number_pages; $i++) {
    crawlData($url, $select_category, $i, $fileName);
}

function crawlData($url, $select_category, $page, $fileName)
{
    if (MyLib::get_data($url, $content)) {
        $category = ""; //lĩnh vực
        $category_child = ""; //lĩnh vưc con
        $nganh = ""; //ngành nghề
        $company_name = "";
        $email = "";
        $emailCaNhan = "";
        $hoTen = "";
        $chucvu = "";
        $dienthoai = "";
        $website = "";
        $phone = "";
        $dom = new Document();
        $dom->load($content);
        foreach (categories($dom) as $key => $cate) {
            //get linh vực
            $category = $cate->text();
            switch ($select_category) {
                case $key:
                    $url = $cate->getAttribute('href');
                    if (count(childCategories($url)) > 0 && childCategories($url) !=null) {
                        $result = [];
                        foreach (childCategories($url) as $key => $childcate) {
                            //get lĩnh vực con
                            $category_child_ = $childcate->text();
                            if ($category_child_ != '' && isset($category_child_)) {
                                $category_child = $category_child_;
                            }
                            $childCategoryDetails = $childcate->parent()->find('div div div div div a');
                            if (count($childCategoryDetails) > 0) {
                                foreach ($childCategoryDetails as $key => $detail) {
                                    $urlNganh = $detail->getAttribute('href');
                                    content_company($urlNganh, $page, $contents);
                                    if ($contents ==""){
                                        continue;
                                    }
                                        foreach ($contents as $content) {

                                            $url_cty = $content->find('h2 a')[0]->getAttribute('href');
                                            $nganh = $detail->text();
                                            $company_name = company_name($content)->text();
                                            $address = address($content)->text();
                                            $phone = phoneNumber($content)->text();
                                            $email = email($content);
                                            $website = website($content);

                                             if (MyLib::get_data($url_cty, $content)) {
                                                $dom = new Document();
                                                $dom->load($content);
                                                $elms = $dom->find('#listing_detail_right div')[0];
                                                $find_p = $elms->find('p');
                                                if (count($find_p) > 0 && $find_p != null) {
                                                
                                                    foreach ($find_p as $key => $p) {
                                                       $title = $p->text();
                                                       if ($title === "Email:") {
                                                            $index = $key + 1;
                                                            $em = $find_p[$index];
                                                            $emailCaNhan .= $em->find('a')[0]->text();
                                                       }
                                                       if ($title === "Di động:") {
                                                            $index = $key + 1;
                                                            $dienthoai .= $find_p[$index]->text();
                                                       }
                                                       if ($title === "Họ tên:") {
                                                           $index = $key + 1;
                                                           $hoTen .= $find_p[$index]->text();
                                                       }
                                                       if ($title === "Chức vụ:") {
                                                           $index = $key + 1;
                                                           $chucvu .= $find_p[$index]->text();
                                                       }

                                                    }
                                                     $result[] = [
                                                            'name_company' => $company_name,
                                                            'address' => $address,
                                                            'phone' => $phone,
                                                            'email' => $email,
                                                            'website' => $website,
                                                            'linhvuc' => $category,
                                                            'linhvuccon' => $category_child,
                                                            'nganh' => $nganh,
                                                            'hoTen' => $hoTen,
                                                            'chucvu' => $chucvu,
                                                            'dienthoai' => $dienthoai,
                                                            'emailCaNhan' => $emailCaNhan
                                                      ];

                                                    $chucvu = "";
                                                    $hoTen = "";
                                                    $dienthoai = "";
                                                    $emailCaNhan = "";
                                                }
                                                
                                            } else {
                                                MyLib::println('Cannot get data for this page');
                                            }

                                                //var_dump($result);
                                        }

                                }
                            }

                        }

                    }
                    break;
            }
        }
    } else {
        MyLib::println('Cannot get data for this page');
    }
    echo '<pre>' . (json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) . '</pre>';
    if (!empty($result)) {
        MyLib::exporeExcelTrangVang($result, $page, $fileName);
    }
}

//lĩnh vực
function categories($content)
{
    $content->find('#main_box_niengiamnganh')[0];
    $elms = $content->find('.cell_niengiam h2 a');
    if (count($elms) > 0) {
        return $elms;
    }
}

//lĩnh vực con
function childCategories($url)
{
    if (MyLib::get_data($url, $content)) {
        $dom = new Document();
        $dom->load($content);
        $elms = $dom->find('.niengiampages_box')[1];
        $childcategories = $elms->find('div div p');
        if (count($childcategories) > 0) {
            return $childcategories;
        }
    } else {
        MyLib::println('Cannot get data for this page');
    }
}

//nôi dung từng ngành
function content_company($url, $page, &$contents)
{

    $urlPage = $url . "?page=" . $page;

    if (MyLib::get_data($urlPage, $content)) {
        $dom = new Document();
        $dom->load($content);
        $Page = $dom->find('#paging a');
        $contents = $dom->find('.boxlistings');
        $countPage = count($Page) - 2;
        $pageTotail = $dom->find('#paging a')[$countPage]->text();
        if ($page > $pageTotail ){
            $contents = "";
        }
    } else {
        MyLib::println('Cannot get data for this page');
    }
}

//tên công ty
function company_name($content)
{
    $company_name = $content->find('h2.company_name')[0];
    return $company_name;
}

//trụ sở chính
function address($content)
{
    $elms = $content->find('.address_listings')[0];
    $address = $elms->find('p.diachisection')[1];
    return $address;
}

//điện thoại
function phoneNumber($content)
{
    $phoneNumber = $content->find('p.thoaisection')[0];
    return $phoneNumber;
}

//email
function email($content)
{
    $emails = $content->find('.listings_phanduoi .email_text a');
    if (count($emails) > 0) {
        foreach ($emails as $email) {
            return $email->getAttribute('title');
        }
    }
}

//nhà thiết kế
function design($content)
{

}

//website
function website($content)
{
    $elms = $content->find('.box_website .website_text a');
    if (count($elms) > 0) {
        foreach ($elms as $elm) {
            return $elm->getAttribute('href');
        }
    }
}

?>