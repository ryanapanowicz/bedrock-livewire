# Bedrock Livewire

This repository is a WordPress sandbox built on [Roots Bedrock](https://roots.io/bedrock/) to test and develop a Livewire-powered theme inside WordPress.

The project combines:
Ryan Apanowicz <ryan.apanowicz@cgphoenix.com>
- Bedrock for WordPress dependency and environment management
- Acorn for Laravel-style bootstrapping inside the theme
- Livewire v4 for reactive UI in WordPress
- A custom child theme, `lativ-child`, for block and component development
- ACF Pro for custom block registration and editor configuration

## Repository Focus

This is not a generic Bedrock starter. The point of the repo is to explore how modern Laravel-style tooling can work inside WordPress, specifically:

- Booting Acorn from a WordPress FSE theme
- Using Livewire components in a WordPress frontend
- Registering custom Gutenberg blocks from the theme using blade views
- Pairing ACF block metadata with Blade-rendered block templates

## Local Setup

1. Start the local environment:

   ```bash
   lando start
   ```

2. Copy environment values and then edit it to add auth keys:

   ```bash
   cp .env.example .env
   ```

3. Install root dependencies:

   ```bash
   lando composer install
   ```
   
4. Add Laravel app key:

   ```bash
   lando wp acorn key:generate
   ```

5. Install the theme's dependencies:

   ```bash
   cd web/app/themes/lativ-child
   lando composer install
   ```

6. Open the site:

   ```text
   https://bedrock-livewire.lndo.site
   ```