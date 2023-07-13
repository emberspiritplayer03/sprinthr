<?php
interface IGroup {
	public function getId();
	public function getName();
	public function getMembers();
	public function addMembers($members);
}
?>