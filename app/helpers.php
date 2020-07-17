<?php
function isLoggedInUserAdmin(){

	return session('user')['type'] === 'admin';
}