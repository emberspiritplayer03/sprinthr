<?php
class G_Excel_Writer extends PHPExcel {
	public function __construct() {
		parent::__construct();
	}
	
	public function write($row, $column, $value, $format) {
		$row++;
		if (is_array($format)) {
			$this->getActiveSheet()->getStyleByColumnAndRow($column, $row)->applyFromArray($format);
		}	
		$this->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $value);
	}
	
	public function setWidth($row, $column, $value) {
		$row++;
		if (strtolower($value) == 'auto') {
			$this->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
		} else {
			$this->getActiveSheet()->getColumnDimensionByColumn($column)->setWidth($value);
		}			
	}
	
	/*
		Usage:
			$file = BASE_PATH . 'application/views/reports/contribution/sss_source.xlsx';
			$objPHPExcel = new G_Excel_Writer();
			$new_sheet = $objPHPExcel->getActiveSheet();
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objReader = $objReader->load($file);
			$read_sheet = $objReader->getActiveSheet();
			$new_sheet = $objPHPExcel->copySheetLayout($read_sheet, $new_sheet);		
	*/
	public function copySheetLayout($read_sheet, $new_sheet) {
		//$new_sheet = $this->getActiveSheet();
/*		foreach ($read_sheet->getRowIterator() as $row) {
		   $cellIterator = $row->getCellIterator();
		   foreach ($cellIterator as $cell) {
			  //if ($cell->getRow() <= 37 && $cell->columnIndexFromString($cell->getColumn()) <= 20) {
			  if (($cell->getValue() != "")) {
				$coord = $cell->getColumn() . ($cell->getRow());//$cell->getCoordinate();		 
				$cells[$cell->getColumn()][($cell->getRow())] = $cell->getValue();										 																			 		 
			  }
		   }
		}*/
		
		$cell_collections = $read_sheet->getCellCollection();
		foreach ($cell_collections as $coord) {
				// FONT
				$style = $read_sheet->getStyle($coord);
				$font = $style->getFont();
				$size = $font->getSize();
				$name = $font->getName();
				$color = $font->getColor();
				$bold = $font->getBold();
				$new_sheet->getStyle($coord)->getFont()->setName($name)
													 ->setSize($size)
													 ->setColor($color)
													 ->setBold($bold);
		
				// ALIGNMENT											 
				$alignment = $style->getAlignment();
				$horizontal = $alignment->getHorizontal();
				$indent = $alignment->getIndent();
				$shrink = $alignment->getShrinkToFit();
				$rotation = $alignment->getTextRotation();
				$wrap = $alignment->getWrapText();
				$new_sheet->getStyle($coord)->getAlignment()->setHorizontal($horizontal)
															->setIndent($indent)
															->setShrinkToFit($shrink)
															->setTextRotation($rotation)	
															->setWrapText($wrap);
				
				// BORDERS
/*				$border_top = $style->getBorders()->getTop()->getBorderStyle();
				$border_left = $style->getBorders()->getLeft()->getBorderStyle();
				$border_right = $style->getBorders()->getRight()->getBorderStyle();
				$border_bottom = $style->getBorders()->getBottom()->getBorderStyle();
				$new_sheet->getStyle($coord)->getBorders()->getTop()->setBorderStyle($border_top);
				$new_sheet->getStyle($coord)->getBorders()->getLeft()->setBorderStyle($border_left);
				$new_sheet->getStyle($coord)->getBorders()->getRight()->setBorderStyle($border_right);
				$new_sheet->getStyle($coord)->getBorders()->getBottom()->setBorderStyle($border_bottom);*/
				
				// FILL
/*				$fill = $style->getFill();
				$end_color = $fill->getEndColor();
				$fill_type = $fill->getFillType();
				$rotation = $fill->getRotation();
				$start_color = $fill->getStartColor();
				$new_sheet->getStyle($coord)->getFill()->setEndColor($end_color)
													 ->setFillType($fill_type)
													 ->setRotation($rotation)
													 ->setStartColor($start_color);*/	
													 
				// NUMBER FORMAT
				$number_format = $style->getNumberFormat();
				$format_code = $number_format->getFormatCode();
				$built_format_code = $number_format->getBuiltInFormatCode();
				$new_sheet->getStyle($coord)->getNumberFormat()->setFormatCode($format_code)
													 ->setBuiltInFormatCode($built_format_code);	
		}
		
/*		// MERGED CELLS
		$merge_cells = $read_sheet->getMergeCells();
		foreach ($merge_cells as $cell => $value) {
			$new_sheet->mergeCells($cell);	
		}
		
		// COLUMN WIDTH
		$column_dimensions = $read_sheet->getColumnDimensions();
		foreach ($column_dimensions as $column => $column_dimension) {
			$new_sheet->getColumnDimension($column)->setWidth($column_dimension->getWidth());
		}
		
		$row_dimensions = $read_sheet->getRowDimensions();
		foreach ($row_dimensions as $row => $row_dimension) {
			$new_sheet->getRowDimension($row)->setRowHeight($row_dimension->getRowHeight());
		}
		
		
		foreach ($cells as $column => $values) {
			foreach ($values as $row => $value) {
				$new_sheet->setCellValue($column . ($row), $value);		
			}
		}	*/
		
		return $new_sheet;	
	}
}
?>