<?php

return array(
    "base_url" => "http://admins.ranbandokmaisod.com/gauth/auth",
    "providers" => array(
        "Google" => array(
            "enabled" => true,
            "keys" => array(
                "id" => "937095495765-ffnmq4j6rs7b8n20a577oie849oc41s9.apps.googleusercontent.com",
                "secret" => "8NVl6-RFUnUOn5C7NdYn0RMR"),
            "scope" => "https://www.googleapis.com/auth/userinfo.profile " . // optional
            "https://www.googleapis.com/auth/userinfo.email",
        )
    )
);
