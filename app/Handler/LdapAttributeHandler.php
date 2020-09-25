<?php


namespace App\Handler;


use App\Models\Security\User;
use Adldap\Models\User as LdapUser;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LdapAttributeHandler
{
    /**
     * Synchronizes ldap attributes to the specified model.
     *
     * @param LdapUser     $ldapUser
     * @param User $eloquentUser
     *
     * @return void
     */
    public function handle(LdapUser $ldapUser, User $eloquentUser)
    {
        $eloquentUser->name              = isset( $ldapUser->givenname ) ? toUpper( $ldapUser->getFirstAttribute('givenname') ) : 'NO NAME';
        $eloquentUser->surname           = isset( $ldapUser->sn ) ? toUpper( $ldapUser->getFirstAttribute('sn') ) : 'NO NAME';
        $eloquentUser->email             = isset( $ldapUser->userprincipalname ) ? toLower( $ldapUser->getFirstAttribute('userprincipalname') ) : null;
        $eloquentUser->username          = isset( $ldapUser->samaccountname ) ? toLower( $ldapUser->getFirstAttribute('samaccountname') ) : Str::random(10);
        $eloquentUser->description       = isset( $ldapUser->description ) ? toUpper( $ldapUser->getFirstAttribute('description') ) : 'SIN DESCRIPCIPCIÓN';
        $eloquentUser->dependency        = isset( $ldapUser->physicaldeliveryofficename ) ? toUpper( $ldapUser->getFirstAttribute('physicaldeliveryofficename') ) : 'IDRD';
        $eloquentUser->company           = isset( $ldapUser->company ) ? toUpper( $ldapUser->getFirstAttribute('company') ) : 'SEDE EXTERNA';
        if ( strlen( $ldapUser->getTelephoneNumber() ) > 0 && strlen( $ldapUser->getTelephoneNumber() ) <= 4 ) {
            $eloquentUser->phone         = '6605400';
            $eloquentUser->ext           = $ldapUser->getTelephoneNumber();
        } elseif ( strlen( $ldapUser->getTelephoneNumber() ) > 4 ) {
            $eloquentUser->phone         = $ldapUser->getTelephoneNumber();
        }
        $eloquentUser->expires_at        = isset($ldapUser->accountexpires[0]) ? ldapDateToCarbon( $ldapUser->getFirstAttribute('accountexpires') ) : Carbon::now();
    }
}