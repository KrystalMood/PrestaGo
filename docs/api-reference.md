# API Reference - PrestaGo

This document provides information about the API endpoints available in the PrestaGo application.

## Authentication

All API requests require authentication using Laravel Sanctum.

### Obtaining API Token

```
POST /api/login
```

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "token": "1|example-token-string",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "role": "mahasiswa"
  }
}
```

**Using the token:**

Include the token in all API requests as a Bearer token:

```
Authorization: Bearer 1|example-token-string
```

## Achievement Endpoints

### List Achievements

```
GET /api/achievements
```

**Query Parameters:**
- `status` (optional): Filter by verification status (pending, verified, rejected)
- `type` (optional): Filter by achievement type (academic, non-academic)
- `page` (optional): Page number for pagination
- `per_page` (optional): Number of items per page (default: 15)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "First Place Programming Competition",
      "type": "academic",
      "level": "national",
      "date": "2023-05-15",
      "status": "verified",
      "user_id": 1,
      "user": {
        "id": 1,
        "name": "Student Name"
      }
    }
  ],
  "links": {
    "first": "http://example.com/api/achievements?page=1",
    "last": "http://example.com/api/achievements?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://example.com/api/achievements",
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

### Get Achievement Details

```
GET /api/achievements/{id}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "title": "First Place Programming Competition",
    "description": "Won first place in the annual coding competition",
    "type": "academic",
    "level": "national",
    "date": "2023-05-15",
    "status": "verified",
    "attachments": [
      {
        "id": 1,
        "file_name": "certificate.pdf",
        "file_path": "/storage/attachments/certificate.pdf"
      }
    ],
    "user": {
      "id": 1,
      "name": "Student Name"
    }
  }
}
```

### Create Achievement

```
POST /api/achievements
```

**Request Body:**
```json
{
  "title": "First Place Programming Competition",
  "description": "Won first place in the annual coding competition",
  "type": "academic",
  "level": "national",
  "date": "2023-05-15",
  "competition_name": "National Coding Championship"
}
```

**Note:** Attachments should be sent as `multipart/form-data`

**Response:**
```json
{
  "message": "Achievement created successfully",
  "data": {
    "id": 1,
    "title": "First Place Programming Competition",
    "description": "Won first place in the annual coding competition",
    "type": "academic",
    "level": "national",
    "date": "2023-05-15",
    "status": "pending"
  }
}
```

### Update Achievement

```
PUT /api/achievements/{id}
```

**Request Body:**
```json
{
  "title": "First Place Programming Competition (Updated)",
  "description": "Updated description"
}
```

**Response:**
```json
{
  "message": "Achievement updated successfully",
  "data": {
    "id": 1,
    "title": "First Place Programming Competition (Updated)",
    "description": "Updated description",
    "type": "academic",
    "level": "national",
    "date": "2023-05-15",
    "status": "pending"
  }
}
```

### Delete Achievement

```
DELETE /api/achievements/{id}
```

**Response:**
```json
{
  "message": "Achievement deleted successfully"
}
```

## Error Handling

All API endpoints return appropriate HTTP status codes:

- `200 OK`: Request succeeded
- `201 Created`: Resource created successfully
- `400 Bad Request`: Invalid request parameters
- `401 Unauthorized`: Authentication required or invalid token
- `403 Forbidden`: Permission denied
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation error
- `500 Server Error`: Internal server error

### Error Response Format

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": [
      "The title field is required."
    ]
  }
}
```

## Rate Limiting

API requests are rate-limited to 60 requests per minute per user. 