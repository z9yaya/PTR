<?php 
if (session_id() == '')
{
  session_start();
}
if (empty($_SESSION['INITIALSETUP']))
{
    $_SESSION['INITIALSETUP'] = 1;
}