<?php
/**
 *+----------------------------------------------------------
 * Export Excel;
 *+----------------------------------------------------------
 * @param $expTitle     string 文件名 
 *+----------------------------------------------------------
 * @param $expCellName  array  列名
 *+----------------------------------------------------------
 * @param $expTableData array  数据集
 *+----------------------------------------------------------
 */
function exportExcel($expTitle,$expCellName,$expTableData,$file_title=''){

    ob_end_clean();  //清空缓存 此句不可少

    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    vendor("PHPExcel180.PHPExcel");
    $objPHPExcel = new PHPExcel();
    $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
    $Atitle=$file_title?$file_title:$expTitle;//首行标题
    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $Atitle.'  Export time：'.date('Y-m-d H:i:s'));  

    // 设置excel的属性：
    // 创建人
    $objPHPExcel->getProperties()->setCreator("admin");
    // 最后修改人
     $objPHPExcel->getProperties()->setLastModifiedBy("admin");
    // 标题
    $objPHPExcel->getProperties()->setTitle($expTitle);
    // 题目
    $objPHPExcel->getProperties()->setSubject($expTitle);
    // 描述
    $objPHPExcel->getProperties()->setDescription("create in ：".date('Y-m-d H:m:s'));
    // 关键字
    $objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
    // 种类
    // $objPHPExcel->getProperties()->setCategory("Test result file");

    // 设置sheet的name
    $objPHPExcel->getActiveSheet()->setTitle($expTitle);

    for($i=0;$i<$cellNum;$i++){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
    } 

    // Miscellaneous glyphs, UTF-8   
    for($i=0;$i<$dataNum;$i++){
      for($j=0;$j<$cellNum;$j++){
        $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
      }             
    }  

    // 在默认sheet后，创建一个worksheet
    // $objPHPExcel->createSheet();
    
    header('Content-Type: application/vnd.ms-excel;charset=utf-8;');
    header('Content-Disposition: attachment;filename="'.$expTitle.'.xlsx";');
    header('Cache-Control: max-age=0;');

   // $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);      // 非2007格式

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);   // 作为一个支持2007的
    $objWriter->save("php://output");
    exit;   
}
 
/**
 *+----------------------------------------------------------
 * Import Excel
 *+----------------------------------------------------------
 * @param  $file   upload file $_FILES
 *+----------------------------------------------------------
 * @return array   array("error","message")
 *+----------------------------------------------------------     
 */   
function importExecl($file){ 
    if(!file_exists($file)){ 
        return array("error"=>0,'message'=>'file not found!');
    } 
    Vendor("PHPExcel180.PHPExcel.IOFactory"); 
    $objReader = PHPExcel_IOFactory::createReader('Excel5'); 
    try{
        $PHPReader = $objReader->load($file);
    }catch(Exception $e){}
    if(!isset($PHPReader)) return array("error"=>0,'message'=>'read error!');
    $allWorksheets = $PHPReader->getAllSheets();
    $i = 0;
    foreach($allWorksheets as $objWorksheet){
        $sheetname=$objWorksheet->getTitle();
        $allRow = $objWorksheet->getHighestRow();//how many rows
        $highestColumn = $objWorksheet->getHighestColumn();//how many columns
        $allColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $array[$i]["Title"] = $sheetname; 
        $array[$i]["Cols"] = $allColumn; 
        $array[$i]["Rows"] = $allRow; 
        $arr = array();
        $isMergeCell = array();
        foreach ($objWorksheet->getMergeCells() as $cells) {//merge cells
            foreach (PHPExcel_Cell::extractAllCellReferencesInRange($cells) as $cellReference) {
                $isMergeCell[$cellReference] = true;
            }
        }
        for($currentRow = 1 ;$currentRow<=$allRow;$currentRow++){ 
            $row = array(); 
            for($currentColumn=0;$currentColumn<$allColumn;$currentColumn++){;                
                $cell =$objWorksheet->getCellByColumnAndRow($currentColumn, $currentRow);
                $afCol = PHPExcel_Cell::stringFromColumnIndex($currentColumn+1);
                $bfCol = PHPExcel_Cell::stringFromColumnIndex($currentColumn-1);
                $col = PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                $address = $col.$currentRow;
                $value = $objWorksheet->getCell($address)->getValue();
                if(substr($value,0,1)=='='){
                    return array("error"=>0,'message'=>'can not use the formula!');
                    exit;
                }
                if($cell->getDataType()==PHPExcel_Cell_DataType::TYPE_NUMERIC){
                    $cellstyleformat=$cell->getParent()->getStyle( $cell->getCoordinate() )->getNumberFormat();
                    $formatcode=$cellstyleformat->getFormatCode();
                    if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode)) {
                        $value=gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($value));
                    }else{
                        $value=PHPExcel_Style_NumberFormat::toFormattedString($value,$formatcode);
                    }                
                }
                if($isMergeCell[$col.$currentRow]&&$isMergeCell[$afCol.$currentRow]&&!empty($value)){
                    $temp = $value;
                }elseif($isMergeCell[$col.$currentRow]&&$isMergeCell[$col.($currentRow-1)]&&empty($value)){
                    $value=$arr[$currentRow-1][$currentColumn];
                }elseif($isMergeCell[$col.$currentRow]&&$isMergeCell[$bfCol.$currentRow]&&empty($value)){
                    $value=$temp;
                }
                $row[$currentColumn] = $value; 
            } 
            $arr[$currentRow] = $row; 
        } 
        $array[$i]["Content"] = $arr; 
        $i++;
    } 
    spl_autoload_register(array('Think','autoload'));//must, resolve ThinkPHP and PHPExcel conflicts
    unset($objWorksheet); 
    unset($PHPReader); 
    unset($PHPExcel); 
    unlink($file); 
    return array("error"=>1,"data"=>$array); 
}
