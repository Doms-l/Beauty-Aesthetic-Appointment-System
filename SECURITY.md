# Security notes

This starter project uses Laravel's built-in security features:

- CSRF tokens on POST/PUT/PATCH/DELETE forms.
- Request validation before database writes.
- Password hashing through the User model's `hashed` cast.
- Unique email validation and database unique constraint.
- Session regeneration after login/registration.
- Session invalidation and CSRF token regeneration on logout.
- Login and registration rate limiting.
- Role middleware for client, staff, and admin areas.
- Client appointment ownership check before cancellation.
- Eloquent parameter binding instead of raw SQL for normal CRUD operations.
- Mass-assignment protection through `$fillable`.

Before real deployment:
- Replace demo passwords.
- Set APP_DEBUG=false.
- Use HTTPS.
- Use a strong production database password.
- Configure secure cookies and trusted hosts as appropriate for deployment.
- Add email verification and password reset.
- Add audit logs for sensitive admin actions.
- Back up the database.
- Do not commit `.env` or production secrets to Git.
