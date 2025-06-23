# Hermés CMS

## Requisitos
- Docker e Docker Compose **ou** Laravel Sail
- PHP 8.2+
- Composer
- Node.js e npm (para assets frontend)

## Instalação

### 1. Clone o repositório
```bash
 git clone <[URL_DO_REPOSITORIO](https://github.com/marcellopato/backend-hermes.git)>
 cd hermes
```

### 2. Copie o arquivo de ambiente
```bash
cp .env.example .env
```

### 3. Suba o ambiente com Docker Compose
```bash
docker-compose up -d
```

#### Ou usando Laravel Sail
```bash
./vendor/bin/sail up -d
```

### 4. Instale as dependências PHP e JS
```bash
composer install
npm install && npm run build
```

### 5. Gere a chave da aplicação
```bash
php artisan key:generate
```

### 6. Rode as migrations e seeders
```bash
php artisan migrate --seed
```

Ou apenas os seeders:
```bash
php artisan db:seed
```

### 7. Credenciais de acesso
Os seeders criam usuários de teste. Veja em `database/seeders/UserWithRolesSeeder.php` as credenciais geradas, por exemplo:
- **Email:** admin@example.com
- **Senha:** password

(Altere conforme o que está no seu seeder)

## Rodando os testes

Execute todos os testes automatizados:
```bash
php artisan test
```

### Resultado esperado
```
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                                                                                                                                                                    0.13s  

   PASS  Tests\Feature\Auth\AuthenticationTest
  ✓ login screen can be rendered                                                                                                                                                                                         8.01s  
  ✓ users can authenticate using the login screen                                                                                                                                                                        7.21s  
  ✓ users can not authenticate with invalid password                                                                                                                                                                     0.69s  
  ✓ navigation menu can be rendered                                                                                                                                                                                      0.48s  
  ✓ users can logout                                                                                                                                                                                                     0.26s  

   PASS  Tests\Feature\Auth\EmailVerificationTest
  ✓ email verification screen can be rendered                                                                                                                                                                            0.19s  
  ✓ email can be verified                                                                                                                                                                                                0.41s  
  ✓ email is not verified with invalid hash                                                                                                                                                                              0.53s  

   PASS  Tests\Feature\Auth\PasswordConfirmationTest
  ✓ confirm password screen can be rendered                                                                                                                                                                              0.45s  
  ✓ password can be confirmed                                                                                                                                                                                            0.44s  
  ✓ password is not confirmed with invalid password                                                                                                                                                                      0.53s  

   PASS  Tests\Feature\Auth\PasswordResetTest
  ✓ reset password link screen can be rendered                                                                                                                                                                           0.29s  
  ✓ reset password link can be requested                                                                                                                                                                                 0.90s  
  ✓ reset password screen can be rendered                                                                                                                                                                                0.23s  
  ✓ password can be reset with valid token                                                                                                                                                                               1.01s  

   PASS  Tests\Feature\Auth\PasswordUpdateTest
  ✓ password can be updated                                                                                                                                                                                              0.86s  
  ✓ correct password must be provided to update password                                                                                                                                                                 0.69s  

   PASS  Tests\Feature\Auth\RegistrationTest
  ✓ registration screen can be rendered                                                                                                                                                                                  0.31s  
  ✓ new users can register                                                                                                                                                                                               0.57s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                                                                                                                        0.20s  

   PASS  Tests\Feature\PostManagerTest
  ✓ admin can create post                                                                                                                                                                                                1.42s  
  ✓ admin can edit post                                                                                                                                                                                                  0.65s  
  ✓ admin can delete post                                                                                                                                                                                                0.49s  
  ✓ validation required fields                                                                                                                                                                                           0.58s  

   PASS  Tests\Feature\ProductManagerTest
  ✓ admin can create product                                                                                                                                                                                             0.88s  
  ✓ admin can edit product                                                                                                                                                                                               0.69s  
  ✓ admin can delete product                                                                                                                                                                                             0.58s  
  ✓ validation required fields                                                                                                                                                                                           0.58s  
  ✓ admin can upload product image                                                                                                                                                                                       0.85s  

   PASS  Tests\Feature\ProfileTest
  ✓ profile page is displayed                                                                                                                                                                                            0.71s  
  ✓ profile information can be updated                                                                                                                                                                                   0.43s  
  ✓ email verification status is unchanged when the email address is unchanged                                                                                                                                           0.25s  
  ✓ user can delete their account                                                                                                                                                                                        0.50s  
  ✓ correct password must be provided to delete account                                                                                                                                                                  0.74s  

  Tests:    35 passed (107 assertions)
  Duration: 36.03s
```

## Observações
- O projeto utiliza Livewire, Jodit Editor, Spatie Permission e autenticação padrão do Laravel.
- Para dúvidas ou problemas, consulte os seeders ou abra uma issue.
