# Setup

- Clone the repository
- Run `make first`
- Update .env with Twilio settings
- Edit line 135 of SMS.php to return a valid recipient number
- Run `make setup`, this will create a user with the email `test@user.com` and a password of `123456`
- Run `make queue`
- Go to localhost:8080/sms

