<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ActiveRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->ldap
        ? [
            'objectclass'      =>      $this->ldap->getAttribute('objectclass'),
            'buildingname'      =>      $this->ldap->getAttribute('buildingname'),
            'c'      =>      $this->ldap->getAttribute('c'),
            'cn'      =>      $this->ldap->getFirstAttribute('cn'),
            'co'      =>      $this->ldap->getAttribute('co'),
            'comment'      =>      $this->ldap->getAttribute('comment'),
            'commonname'      =>      $this->ldap->getAttribute('commonname'),
            'company'      =>      $this->ldap->getFirstAttribute('company'),
            'description'      =>      $this->ldap->getFirstAttribute('description'),
            'distinguishedname'      =>      $this->ldap->getFirstAttribute('distinguishedname'),
            'dn'      =>      $this->ldap->getAttribute('dn'),
            'department'      =>      $this->ldap->getAttribute('department'),
            'displayname'      =>      $this->ldap->getFirstAttribute('displayname'),
            'facsimiletelephonenumber'      =>      $this->ldap->getAttribute('facsimiletelephonenumber'),
            'fax'      =>      $this->ldap->getAttribute('fax'),
            'friendlycountryname'      =>      $this->ldap->getAttribute('friendlycountryname'),
            'givenname'      =>      $this->ldap->getFirstAttribute('givenname'),
            'homephone'      =>      $this->ldap->getAttribute('homephone'),
            'homepostaladdress'      =>      $this->ldap->getAttribute('homepostaladdress'),
            'info'      =>      $this->ldap->getAttribute('info'),
            'initials'      =>      $this->ldap->getAttribute('initials'),
            'ipphone'      =>      $this->ldap->getAttribute('ipphone'),
            'l'      =>      $this->ldap->getAttribute('l'),
            'mail'      =>      $this->ldap->getFirstAttribute('mail'),
            'mailnickname'      =>      $this->ldap->getAttribute('mailnickname'),
            'rfc822mailbox'      =>      $this->ldap->getAttribute('rfc822mailbox'),
            'mobile'      =>      $this->ldap->getAttribute('mobile'),
            'mobiletelephonenumber'      =>      $this->ldap->getAttribute('mobiletelephonenumber'),
            'name'      =>      $this->ldap->getFirstAttribute('name'),
            'othertelephone'      =>      $this->ldap->getAttribute('othertelephone'),
            'ou'      =>      $this->ldap->getAttribute('ou'),
            'pager'      =>      $this->ldap->getAttribute('pager'),
            'pagertelephonenumber'      =>      $this->ldap->getAttribute('pagertelephonenumber'),
            'physicaldeliveryofficename'      =>      $this->ldap->getFirstAttribute('physicaldeliveryofficename'),
            'postaladdress'      =>      $this->ldap->getAttribute('postaladdress'),
            'postalcode'      =>      $this->ldap->getAttribute('postalcode'),
            'postofficebox'      =>      $this->ldap->getAttribute('postofficebox'),
            'samaccountname'      =>      toLower($this->ldap->getFirstAttribute('samaccountname')),
            'serialnumber'      =>      $this->ldap->getAttribute('serialnumber'),
            'sn'      =>      $this->ldap->getFirstAttribute('sn'),
            'surname'      =>      $this->ldap->getAttribute('surname'),
            'st'      =>      $this->ldap->getAttribute('st'),
            'stateorprovincename'      =>      $this->ldap->getAttribute('stateorprovincename'),
            'street'      =>      $this->ldap->getAttribute('street'),
            'streetaddress'      =>      $this->ldap->getAttribute('streetaddress'),
            'telephonenumber'      =>    (int)  $this->ldap->getFirstAttribute('telephonenumber'),
            'title'      =>      $this->ldap->getAttribute('title'),
            'uid'      =>      $this->ldap->getAttribute('uid'),
            'url'      =>      $this->ldap->getAttribute('url'),
            'userprincipalname'      =>      toLower($this->ldap->getFirstAttribute('userprincipalname')),
            'wwwhomepage'      =>      $this->ldap->getAttribute('wwwhomepage'),
            'instancetype'      =>     (int)  $this->ldap->getFirstAttribute('instancetype'),
            'whencreated'      =>     ldapFormatDate( $this->ldap->getFirstAttribute('whencreated') ),
            'whenchanged'      =>     ldapFormatDate( $this->ldap->getFirstAttribute('whenchanged') ),
            'usncreated'      =>     (int) $this->ldap->getFirstAttribute('usncreated'),
            'memberof'      =>      $this->ldap->getAttribute('memberof'),
            'usnchanged'      =>     (int) $this->ldap->getFirstAttribute('usnchanged'),
            'objectguid'      =>      $this->ldap->getConvertedGuid(),
            'useraccountcontrol'      =>    (int)  $this->ldap->getFirstAttribute('useraccountcontrol'),
            'badpwdcount'      =>      (int) $this->ldap->getFirstAttribute('badpwdcount'),
            'codepage'      =>      (int) $this->ldap->getFirstAttribute('codepage'),
            'countrycode'      =>      (int) $this->ldap->getFirstAttribute('countrycode'),
            'badpasswordtime'      =>    ldapDateToCarbon(  $this->ldap->getFirstAttribute('badpasswordtime') ),
            'lastlogoff'      =>      $this->ldap->getFirstAttribute('lastlogoff'),
            'lastlogon'      =>      ldapDateToCarbon( $this->ldap->getFirstAttribute('lastlogon') ),
            'pwdlastset'      =>      ldapDateToCarbon( $this->ldap->getFirstAttribute('pwdlastset') ),
            'primarygroupid'      =>   (int) $this->ldap->getFirstAttribute('primarygroupid'),
            'objectsid'      =>      $this->ldap->getConvertedSid(),
            'accountexpires'      =>     ldapDateToCarbon(  $this->ldap->getFirstAttribute('accountexpires') ),
            'logoncount'      =>      (int) $this->ldap->getFirstAttribute('logoncount'),
            'samaccounttype'      =>   (int)   $this->ldap->getFirstAttribute('samaccounttype'),
            'lockouttime'      =>      (int) $this->ldap->getFirstAttribute('lockouttime'),
            'objectcategory'      =>      $this->ldap->getFirstAttribute('objectcategory'),
            'dscorepropagationdata'      =>     ldapFormatDate( $this->ldap->getAttribute('dscorepropagationdata') ),
            'lastlogontimestamp'      =>      ldapDateToCarbon( $this->ldap->getFirstAttribute('lastlogontimestamp') ),
            'msds-lastsuccessfulinteractivelogontime'      =>   ldapDateToCarbon( $this->ldap->getFirstAttribute('msds-lastsuccessfulinteractivelogontime') ),
            'msds-lastfailedinteractivelogontime'      =>      ldapDateToCarbon( $this->ldap->getFirstAttribute('msds-lastfailedinteractivelogontime') ),
            'msds-failedinteractivelogoncount'      =>      (int) $this->ldap->getFirstAttribute('msds-failedinteractivelogoncount'),
            'msds-failedinteractivelogoncountatlastsuccessfullogon'      =>      (int) $this->ldap->getFirstAttribute('msds-failedinteractivelogoncountatlastsuccessfullogon'),
        ]
        : [];
    }
}
