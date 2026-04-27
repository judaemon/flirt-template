FLIRT Template

Filament
Livewire
Inertia
Laravel
Tailwind CSS

setup your nginx (needed for nms auth package)
you can change the port to 80 to be compatible with nginx
you can also modify vite if your running in  CORS policy issue
env
```bash
cp .env.example .env
```

install nms package and dependencies 
```bash
composer config --global gitlab-token.nexus.nmscreative.com glpat-sampletoken
composer i
npm i
php artisan migrate
```

run dev server
```bash
composer run dev
```

create new user
```bash
php artisan make:filament-user
```

typescript formatter
```bash
# Format all files
npx @biomejs/biome format --write

# Format specific files
npx @biomejs/biome format --write <files>

# Lint files and apply safe fixes to all files
npx @biomejs/biome lint --write

# Lint files and apply safe fixes to specific files
npx @biomejs/biome lint --write <files>

# Format, lint, and organize imports of all files
npx @biomejs/biome check --write

# Format, lint, and organize imports of specific files
npx @biomejs/biome check --write <files>
```

PHP formatter
```bash
./vendor/bin/pint
```
