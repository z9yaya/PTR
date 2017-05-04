<?php include '../functions/tools.php';
$stores = GrabAllData('SELECT ID, SUBURB, STATE, MANAGER FROM STORES');
$dropdown = '<option value="">Please select a store</option>';
for ($i = 0; $i < count($stores['ID']); $i++)
{
    if (empty($stores['MANAGER'][$i])) {
        $dropdown .= "<option value = '{$stores['ID'][$i]}'>{$stores['ID'][$i]} | {$stores['SUBURB'][$i]} | {$stores['STATE'][$i]}</option>";
    } else {
        $dropdown .= "<option manager='{$stores['MANAGER'][$i]}' value = '{$stores['ID'][$i]}'>{$stores['ID'][$i]} | {$stores['SUBURB'][$i]} | {$stores['STATE'][$i]}</option>";
    }
}
echo $dropdown;