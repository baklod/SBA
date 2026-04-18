# 🏀 Basketball Match Announcement & Stats System - Setup Guide

## 📋 Quick Start Guide

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 2: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 3: Database Setup

Update your `.env` file with database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=basketball_stats
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Create Admin User

```bash
php artisan tinker
```

Then in the tinker console:

```php
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);

\App\Models\User::create([
    'name' => 'Committee User',
    'email' => 'committee@example.com',
    'password' => bcrypt('password'),
    'role' => 'committee'
]);
```

Exit tinker by typing `exit`

### Step 6: Build Frontend Assets

For development (with hot reload):

```bash
npm run dev
```

For production:

```bash
npm run build
```

### Step 7: Start the Application

In a separate terminal:

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 🔐 Default Login Credentials

**Admin Account:**

- Email: `admin@example.com`
- Password: `password`

**Committee Account:**

- Email: `committee@example.com`
- Password: `password`

⚠️ **IMPORTANT**: Change these passwords immediately after first login!

## 🎯 System Features

### Public Pages (No Login)

- Home page with overview
- Match schedules
- Team rankings
- Team statistics
- Individual player stats
- Highest points leaderboard
- Announcements

### Admin Features

- Full system control
- Manage teams, players, matches
- Update scores and stats
- User management
- Create announcements

### Committee Features

- Update match results
- Add player statistics
- Create announcements
- View upcoming matches

## 📝 Creating Sample Data

You can create sample data using tinker:

```bash
php artisan tinker
```

```php
// Create teams
$team1 = \App\Models\Team::create(['name' => 'Lakers', 'wins' => 5, 'losses' => 2, 'points' => 10]);
$team2 = \App\Models\Team::create(['name' => 'Warriors', 'wins' => 4, 'losses' => 3, 'points' => 8]);

// Create players
\App\Models\Player::create(['team_id' => $team1->id, 'name' => 'John Doe', 'jersey_number' => '23', 'position' => 'Forward']);
\App\Models\Player::create(['team_id' => $team2->id, 'name' => 'Jane Smith', 'jersey_number' => '32', 'position' => 'Guard']);

// Create a match
\App\Models\Match::create([
    'team_a_id' => $team1->id,
    'team_b_id' => $team2->id,
    'match_date' => now()->addDays(7),
    'venue' => 'Main Arena',
    'match_time' => '19:00',
    'status' => 'upcoming'
]);
```

## 🛠 Troubleshooting

### Issue: "Mix manifest not found"

**Solution**: Run `npm run build`

### Issue: "Class not found"

**Solution**: Run `composer dump-autoload`

### Issue: Migration errors

**Solution**: Run `php artisan migrate:fresh` (⚠️ This will delete all data)

### Issue: Access denied for user

**Solution**: Check your database credentials in `.env`

## 📂 Project Structure

```
├── app/
│   ├── Http/Controllers/     # All controllers
│   ├── Models/              # Database models
│   └── Http/Middleware/     # Custom middleware
├── database/migrations/     # Database migrations
├── resources/
│   ├── js/
│   │   ├── Layouts/        # Vue layout components
│   │   └── Pages/          # Vue page components
│   └── views/              # Blade templates
└── routes/web.php          # All routes
```

## 🚀 Next Steps

1. Login as admin
2. Create teams via Admin Dashboard
3. Add players to teams
4. Schedule matches
5. Update match results and scores
6. Add player statistics
7. Create announcements

## 📧 Support

For issues or questions, check:

- Laravel documentation: https://laravel.com/docs
- Vue.js documentation: https://vuejs.org
- Inertia.js documentation: https://inertiajs.com

---

🏀 **Happy Managing Your Basketball League!**
