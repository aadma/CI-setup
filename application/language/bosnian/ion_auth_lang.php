<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - Croatian
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Translation: primjeri
*		info@primjeri.com
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  Croatian language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful'] 	  	 = 'Račun je uspješno kreiran';
$lang['account_creation_unsuccessful'] 	 	 = 'Račun nije kreiran';
$lang['account_creation_duplicate_email'] 	 = 'Email je već iskorišten ili pogrešan';
$lang['account_creation_duplicate_username'] = 'Korisničko ime je već iskorišteno ili pogrešno';
$lang['account_creation_missing_default_group'] = 'Početna grupa nije izabrana';
$lang['account_creation_invalid_default_group'] = 'Pogrešno ime početne grupe';

//register via ajax
$lang['entire_form']  = 'Molimo popunite formu u cijelosti';

// Password
$lang['password_change_successful'] 	 	 = 'Lozinka uspješno promjenjena';
$lang['password_change_unsuccessful'] 	  	 = 'Lozinka nije promjenjena';
$lang['forgot_password_successful'] 	 	 = 'Email za poništenje lozinke je poslan';
$lang['forgot_password_unsuccessful'] 	 	 = 'lozinka nije poništena';

// Activation
$lang['activate_successful'] 		  	     = 'Račun je aktiviran';
$lang['activate_unsuccessful'] 		 	     = 'Aktiviranje računa nije uspjelo';
$lang['deactivate_successful'] 		  	     = 'Račun je deaktiviran';
$lang['deactivate_unsuccessful'] 	  	     = 'Deaktivacija računa nije uspjela';
$lang['activation_email_successful'] 	  	 = 'Email za aktivaciju je poslan';
$lang['activation_email_unsuccessful']   	 = 'Slanje email-a za aktivaciju nije uspjelo';

// Login / Logout
$lang['login_successful'] 		  	         = 'Uspješno ste prijavljeni';
$lang['login_unsuccessful'] 		  	     = 'Prijava nije uspjela';
$lang['login_unsuccessful_not_active'] 		 = 'Račun nije aktivan';
$lang['login_timeout']                       = 'Privremeno zaključano. Pokušajte ponovo kasnije.';
$lang['logout_successful'] 		 	         = 'Uspješno ste odjavljeni';

//Login ajax
$lang['login_ajax']                          =  'Molimo upišite vaš email i lozinku';

// Account Changes
$lang['update_successful'] 		 	         = 'Podaci o računu uspješno su ažurirani';
$lang['update_unsuccessful'] 		 	     = 'Podaci o računu nisu ažurirani';
$lang['delete_successful'] 		 	         = 'Korisnik je obrisan';
$lang['delete_unsuccessful'] 		 	     = 'Brisanje korisnika nije uspjelo';

// Groups
$lang['group_creation_successful']  = 'Grupa uspješno kreirana';
$lang['group_already_exists']       = 'Ime grupe već postoji';
$lang['group_update_successful']    = 'Grupa ažurirana';
$lang['group_delete_successful']    = 'Group izbrisana';
$lang['group_delete_unsuccessful'] 	= 'Grupa nije izbrisana';
$lang['group_delete_notallowed']    = 'Nije dozvoljeno brisanje administratorske grupe';
$lang['group_name_required'] 		= 'Ime grupe je obavezno polje';
$lang['group_name_admin_not_alter'] = 'Ime administratorske grupe se ne može mijenjati';

// Activation Email
$lang['email_activation_subject']            = 'Aktivacija računa';
$lang['email_activate_heading']    = 'Aktivirajte korisnički račun za %s';
$lang['email_activate_subheading'] = 'Molimo kliknite ovaj link %s.';
$lang['email_activate_link']       = 'Aktiviraj račun';
// Forgot Password Email
$lang['email_forgotten_password_subject']    = 'Potvrda o zaboravljenoj lozinci';
$lang['email_forgot_password_heading']    = 'Poništite lozinku za %s';
$lang['email_forgot_password_subheading'] = 'Molimo kliknite ovaj link %s.';
$lang['email_forgot_password_link']       = 'Poništi lozinku';
// New Password Email
$lang['email_new_password_subject']          = 'Nova lozinka';
$lang['email_new_password_heading']    = 'Nova lozinka za %s';
$lang['email_new_password_subheading'] = 'Vaša nova lozinka je: %s';
//register
$lang['registration_heading']='Registracija';
$lang['registration_subheading']='Ispunite podatke';
$lang['registration_signup']='Registruj se';