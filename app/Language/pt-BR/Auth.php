<?php

return [
    // Exceptions
    'unknownAuthenticator'  => '{0} não é um autenticador válido.',
    'unknownUserProvider'   => 'Provedor de usuário não pôde ser determinado.',
    'invalidUser'           => 'Não foi possível localizar o usuário especificado.',
    'bannedUser'            => 'Você não pode entrar pois está banido no momento.',
    'loggableUser'          => 'Não foi possível recuperar o usuário especificado para login.',
    'badInput'              => 'Ocorreu um erro ao validar seus dados.',

    // Login views
    'loginTitle'        => 'Entrar',
    'loginAction'       => 'Entrar',
    'login'             => 'Login',
    'allow'             => 'Permitir',
    'twoSteps'          => 'Verificação em duas etapas',
    'twoStepsInfo'      => 'Por favor, insira o código de verificação enviado para o seu email.',
    'email'             => 'Endereço de Email',
    'username'          => 'Nome de Usuário',
    'password'          => 'Senha',
    'passwordConfirm'   => 'Senha (Novamente)',
    'rememberMe'        => 'Lembrar de mim',
    'forgotPassword'    => 'Esqueceu sua senha?',
    'useMagicLink'      => 'Usar um Link de Login',
    'magicLinkSubject'  => 'Seu Link de Login',
    'magicTokenNotFound'=> 'Não foi possível verificar o link.',
    'magicLinkExpired'  => 'Desculpe, o link expirou.',
    'checkYourEmail'    => 'Verifique seu e-mail!',
    'magicLinkDetails'  => 'Acabamos de enviar um e-mail com um link de login. É válido por {0} minutos.',
    'success'           => 'Sucesso',

    // Registration views
    'register'          => 'Registrar',
    'registerDisabled'  => 'O registro não é permitido no momento.',
    'registerSuccess'   => 'Bem-vindo(a) a bordo!',

    // Login errors
    'error'             => 'Erro',
    'badAttempt'        => 'Não foi possível fazer o login. Verifique suas credenciais.',
    'loginSuccess'      => 'Bem-vindo(a) de volta!',
    'invalidEmail'      => 'Por favor, verifique se o endereço de email está correto.',

    // Passwords
    'errorPasswordLength'       => 'As senhas devem ter pelo menos {0, number} caracteres.',
    'suggestPasswordLength'     => 'Frases de senha - até 255 caracteres - criam senhas mais seguras e fáceis de lembrar.',
    'errorPasswordCommon'       => 'A senha não deve ser uma senha comum.',
    'errorPasswordPersonal'     => 'As senhas não podem conter informações pessoais refeitas.',
    'errorPasswordTooSimilar'   => 'A senha é muito parecida com o nome de usuário.',
    'errorPasswordPwned'        => 'A senha {0} foi exposta devido a uma violação de dados e foi vista {1, number} vezes em senhas comprometidas.',
    'errorPasswordEmpty'        => 'A senha é obrigatória.',
    'passwordChangeSuccess'     => 'Senha alterada com sucesso',
    'userDoesNotExist'          => 'A senha não foi alterada. O usuário não existe',
    'resetTokenInvalid'         => 'Seu link de redefinição de senha não é válido.',
];