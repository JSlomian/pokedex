# Wymagania

- Docker
- git

# Uruchomienie

1. Pobranie repo
```bash
git clone https://github.com/JSlomian/pokedex.git
cd pokedex
```

2. Zmiana nazwy .env.example na .env

3. Dodanie w .env klucz api
```dotenv
API_KEY=value
```

4. Uruchomienie projektu

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

# Api Routes

1. Pobranie listy zbanowanych pokemonów.
```
GET /api/banned
```

Zwraca listę nazw.
```json
[
    "ditto"
]
```

2. Dodanie zbanowanego pokemona.
```
POST /api/banned wymaga 
```

Wymaga payloadu 
```json
{
    "name": "ditto"
}
```

Zwraca 
```json
{
    "name": "ditto",
    "updated_at": "2026-03-04T20:25:32.000000Z",
    "created_at": "2026-03-04T20:25:32.000000Z",
    "id": 3
}
```

3. Usunięcie z listy zbanowanych 
```
DELETE /api/banned
```

w formacie z nazwą
```
/api/banned/ditto
```

Otrzymamy 204

4. Info o pokemonach
```
POST /api/info
```

Wymaga payloadu z nazwami pokemonow
```json
{
    "pokemons": ["pikachu", "ditto"]
}
```

Odfiltruje zbanowane i jeśli jakieś pozostaną pójdzie poolem zapytanie do pokeapi.
