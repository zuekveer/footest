# FooTest

## 1. Description

This is a test project built with Laravel 10.* to demonstrate proficiency with the Laravel ecosystem, PHP 8.*, configuration management, version control (Git), database management, Nginx, containerization (Docker), testing, and API interaction using Postman.

## 2. Installation

### Step 1: Clone the Repository
Open your terminal and run:
```bash
git clone git@github.com:zuekveer/footest.git
cd footest
```

### Step 2: laucn a build
Open your terminal and run:
```bash
make rebuild
```

### Step 3: migrate migrations
Open your terminal and run: 
```bash
make migrate
```

### Step 4: lauch tests
Open your terminal and run: 
```bash
make phpunit
```

## 3. How to Use the API via Postman


### Get All Notebooks

**METHOD**: `GET` \
**Route**: `customPath/api/v1/notebook`


### Create a Notebook

**METHOD**: `POST` \
**Route**: `customPath/api/v1/notebook/`

```json
Body (JSON):
{
    "fio": "Jason Ragnar Lodbrok",
    "company": "Gmbh Hellvegen",
    "phone": "+123456789",
    "email": "skald@normann.com",
    "birth_date": "1990-01-01",
    "photo": "https://ragnarok.com/norse.jpg"
}
```


### Update a Notebook

**METHOD**: `PUT` \
**Route**: `customPath/api/v1/notebook/{id}/`

```json
Body (JSON):
{
    "fio": "Ivar The Boneless",
    "company": "Kattegat",
    "phone": "+198765432",
    "email": "berserk@legsless.com",
    "birth_date": "1985-05-20",
    "photo": "https://slow.com/turtle.jpg"
}
```

### Get a Single Notebook

**METHOD**: `GET` \
**Route**: `customPath/api/v1/notebook/{id}/`


### Delete a Notebook

**Method**: `DELETE` \
**Route**: `/api/v1/notebook/{id}`


## 4. Contacts
For more information, reach out via Telegram: [t.me/zuekveer](https://t.me/zuekveer)
