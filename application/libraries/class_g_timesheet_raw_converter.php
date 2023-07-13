<?php
/*
 * Not yet used
 */
class G_Timesheet_Raw_Converter extends Timesheet_Raw_Converter_IM {
    public function process($value, $column) {
        if ($column == 'A' && $value != '') {
            $this->current_employee_code = $value;
        }

        if ($this->isIn($value)) {
            $this->current_type = 'in';
        }

        if ($this->isOut($value)) {
            $this->current_type = 'out';
        }

        if ($this->current_type == 'in' && $this->isValidTime($value)) {
            $this->current_time_in = $this->getTime($value);
            $this->current_date_in = $this->getDate($value);
        }

        if ($this->current_type == 'out' && $this->isValidTime($value)) {
            $this->current_time_out = $this->getTime($value);
            $this->current_date_out = $this->getDate($value);
        }
    }
}
?>