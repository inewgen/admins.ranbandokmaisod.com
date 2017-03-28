<?php
    
return array(
    "base_url" => "http://admins.ranbandokmaisod.dev/gauth/auth",
    "providers" => array(
        "Google" => array(
            "enabled" => true,
            "keys" => array(
                "id" => "598465371184-mbc0mkjqgbeamf97j809dhoq8npkoeqf.apps.googleusercontent.com",
                "secret" => "TqD-HevZa_ZuWd-d8GfaRshg"),
            "scope" => "https://www.googleapis.com/auth/userinfo.profile " . // optional
            "https://www.googleapis.com/auth/userinfo.email",
        )
    )
);