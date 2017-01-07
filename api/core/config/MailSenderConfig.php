<?php

/**
 * Configuration of MailSender
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0.
 */
abstract class MailSenderConfig {

    // Basic configuration

    /**
     * SMTP Host
     */
    const HOST = "smtp-mail.outlook.com";

    /**
     * Authentication is necessary?
     */
    const AUTH = true;

    /**
     * Username of account, usually the username is the same of email
     */
    const USERNAME = "hsanotesrobot@outlook.com";

    /**
     * Password of account
     */
    const PASSWORD = "Hs@notes123";

    /**
     * SMTP port, usually 587
     */
    const PORT = 587;

    // From configuration

    /**
     * Email showed to the receiver. Usually the same of username
     */
    const EMAIL = MailSenderConfig::USERNAME;

    /**
     * Name showed to the receiver
     */
    const NAME = "HSA Notes Robot";

    // Message configuration

    /**
     * Message showed to the receiver if he not have a HTML email client
     */
    const ALT_BODY = "Seu cliente de email não suporta mensagens HTML. Por favor visualize a mensagem em um cliente de email com esse recurso";
}