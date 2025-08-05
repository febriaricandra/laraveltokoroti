# RajaOngkir API Key Security Update

## Overview
RajaOngkir API Key telah dipindahkan dari database ke file konfigurasi (.env) untuk meningkatkan keamanan aplikasi.

## Changes Made

### 1. Configuration Setup
- ✅ Added `rajaongkir` configuration in `config/services.php`
- ✅ Added `RAJAONGKIR_API_KEY` to `.env` file
- ✅ Added `RAJAONGKIR_BASE_URL` with default value

### 2. Service Layer Updates
**File: `app/Services/RajaOngkirService.php`**
- ✅ Modified constructor to use `config('services.rajaongkir.api_key')` instead of database
- ✅ Added `baseUrl` from config for flexibility

### 3. Controller Updates
**File: `app/Http/Controllers/Admin/SettingController.php`**
- ✅ Removed API key validation from `store()` method
- ✅ Updated `getProvinces()` and `getCities()` to use config
- ✅ Improved error messages to indicate .env configuration

### 4. Database Updates
**File: `database/seeders/SettingSeeder.php`**
- ✅ Removed `rajaongkir_api_key` from default settings
- ✅ Cleaned up database by removing old API key setting

### 5. View Updates
**File: `resources/views/admin/settings/index.blade.php`**
- ✅ Removed API key input field
- ✅ Added informational section about .env configuration
- ✅ Added API key status indicator
- ✅ Updated JavaScript to automatically load provinces

## Environment Configuration

Add this to your `.env` file:
```env
# RajaOngkir Configuration
RAJAONGKIR_API_KEY=your_api_key_here
RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1
```

## Security Benefits

1. **Environment Isolation**: API keys are now environment-specific
2. **Version Control Safety**: .env files are excluded from git by default
3. **Production Security**: Different API keys can be used for different environments
4. **Configuration Management**: Centralized configuration in Laravel's config system

## Configuration Structure

```php
// config/services.php
'rajaongkir' => [
    'api_key' => env('RAJAONGKIR_API_KEY'),
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
],
```

## Status Check

The admin settings page now shows:
- ✅ **Terkonfigurasi** - if API key is set in .env
- ❌ **Belum Dikonfigurasi** - if API key is missing

## Testing Results

✅ All functionality tested and working:
- Province loading: 34 provinces loaded
- Cities loading: Working with path parameters
- Shipping calculation: JNE, POS, TIKI couriers available
- Same city detection: Working correctly

## Migration Steps for Existing Installations

1. Add `RAJAONGKIR_API_KEY=your_key` to `.env` file
2. Run `php artisan config:clear` to refresh config cache
3. Remove old API key from database (done automatically)
4. Test admin settings page to verify configuration

## Backwards Compatibility

This change is not backwards compatible. The API key MUST be configured in the .env file for the system to work. This is intentional for security reasons.

## Future Enhancements

Consider adding:
- Environment validation command
- API key testing endpoint
- Configuration validation middleware
