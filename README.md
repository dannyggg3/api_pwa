# API PWA - Backend E-commerce con Laravel

## 📋 Descripción

**API PWA** es una API REST backend completa para comercio electrónico desarrollada con Laravel 8. Diseñada para servir como backend de una Progressive Web Application (PWA), proporciona todas las funcionalidades necesarias para una tienda online profesional: gestión de productos con variantes, procesamiento de órdenes, carrito de compras, facturación electrónica compatible con SRI Ecuador, autenticación JWT, y notificaciones por email.

## 🚀 Tipo de Proyecto

**API REST Backend** - E-commerce con Facturación Electrónica

## 🛠️ Tecnologías Utilizadas

- **Laravel 8.75** - Framework PHP MVC
- **PHP 7.3+ / 8.0+** - Lenguaje backend
- **MySQL 5.7+** - Base de datos relacional
- **JWT Authentication** - Autenticación con tokens
- **DOMPDF** - Generación de PDFs
- **Intervention Image** - Procesamiento de imágenes

## 📚 Frameworks y Librerías

### Backend Core
- **Laravel 8.75** - Framework principal
- **php-open-source-saver/jwt-auth 2.1** - Autenticación JWT
- **Laravel Passport 10.4** - OAuth2 (configurado)
- **Laravel Sanctum 2.11** - Autenticación API alternativa

### Generación de Documentos
- **barryvdh/laravel-dompdf 2.0** - PDFs (facturas)
- **picqer/php-barcode-generator 2.4** - Códigos de barras
- **setasign/fpdf 1.8** - Librería FPDF para PDFs
- **Intervention Image 2.7** - Procesamiento de imágenes

### Infraestructura
- **Guzzle HTTP 7.0** - Cliente HTTP
- **Fruitcake CORS 2.0** - Manejo CORS

### DevOps
- **Laravel Sail** - Entorno Docker
- **PHPUnit 9.5** - Testing unitario
- **Laravel Mix 6** - Compilación de assets

## 🏗️ Arquitectura

### Patrón Arquitectónico: MVC REST API-First

```
┌──────────────────────────────────────┐
│    Cliente PWA (Frontend)            │
│    JavaScript + Axios                │
└────────────┬─────────────────────────┘
             │ HTTP/JSON + JWT
             ↓
┌────────────┴─────────────────────────┐
│  API Router (routes/api.php)         │
│  371 líneas de endpoints             │
│  - Middleware: CORS, Auth, Roles     │
└────────────┬─────────────────────────┘
             │
             ↓
┌────────────┴─────────────────────────┐
│  Controllers (34 controladores)      │
│  - Validación JSON                   │
│  - Lógica de negocio                 │
│  - Respuestas consistentes           │
└────────────┬─────────────────────────┘
             │
             ↓
┌────────────┴─────────────────────────┐
│  Models Eloquent (33 modelos)        │
│  - Relaciones (belongsTo, hasMany)   │
│  - Mutadores/Accesores               │
│  - Cálculos (ej: stock variantes)    │
└────────────┬─────────────────────────┘
             │
             ↓
┌────────────┴─────────────────────────┐
│  MySQL Database                      │
│  - Tablas normalizadas               │
│  - Relaciones FK                     │
│  - Índices optimizados               │
└──────────────────────────────────────┘
```

### Arquitectura de Capas

**6 capas principales:**
1. **Capa de Presentación** - JSON API
2. **Capa de Controladores** - 34 controladores REST
3. **Capa de Validación** - Laravel Validator
4. **Capa de Servicios** - Mail (6 mailers), Procesamiento
5. **Capa de Modelos** - 33 modelos Eloquent
6. **Capa de Persistencia** - MySQL con Eloquent ORM

## 📁 Estructura del Proyecto

```
api_pwa/
├── app/
│   ├── Http/Controllers/     # 34 controladores
│   ├── Models/               # 33 modelos Eloquent
│   ├── Mail/                 # 6 clases de notificación
│   ├── Libraries/            # FPDF personalizado
│   └── Providers/            # Service providers
├── routes/
│   └── api.php               # 371 líneas de endpoints
├── database/
│   ├── migrations/           # Esquema de BD
│   └── seeders/              # Datos iniciales
├── public/
│   ├── firmas/               # Firmas digitales
│   ├── logos/                # Logos de empresa
│   └── Sri/                  # Archivos SRI
├── config/
│   ├── jwt.php               # Configuración JWT
│   ├── cors.php              # Configuración CORS
│   └── mail.php              # Configuración email
└── composer.json             # Dependencias PHP
```

## ✨ Características Principales

### 🔐 Autenticación Bifurcada con JWT

**Sistema dual de autenticación:**

1. **Login Administrador** (`POST /api/login`)
   - Email + contraseña
   - Valida rol_id = 1 (Admin)
   - Genera token JWT
   - Retorna usuario + token bearer

2. **Login Cliente** (`POST /api/loginPortal`)
   - Email + contraseña
   - Valida rol_id = 2 (Cliente)
   - Genera token JWT
   - Acceso a funcionalidades de cliente

**Características:**
- Tokens JWT con expiración configurable (60 min)
- Refresh tokens para renovación
- Logout con blacklist de tokens
- Recuperación de contraseña por email

### 📦 Gestión de Productos con Variantes

**Sistema completo de productos:**

- **CRUD de Productos**: 34 controladores
- **Categorías y Marcas**: Organización jerárquica
- **Variantes Múltiples**: Color, talla, stock por variante
- **Imágenes**: Procesamiento con Intervention Image
- **Stock Inteligente**: Cálculo automático desde variantes
- **Búsqueda y Filtros**: Por categoría, marca, precio

**Modelo de Variantes:**
```php
variantes
├── variante_id
├── producto_id (FK)
├── color
├── talla
├── stock (por variante)
├── precio
└── imagen
```

### 🛒 Carrito de Compras

**Sistema de carrito persistente:**
- Vinculado a cliente autenticado
- Items asociados a variante específica
- Gestión de cantidades
- Cálculo de totales en tiempo real
- CRUD completo: agregar, actualizar, eliminar

### 📋 Gestión de Órdenes Completa

**Flujo de órdenes:**

1. **Creación de Orden**
   - Datos de facturación
   - Dirección de entrega
   - Productos y cantidades
   - Método de pago
   - Cálculo de impuestos (IVA 12%, IVA 0%)

2. **Estructura de Orden**
```
orden (cabecera)
├── orden_id
├── cliente_id
├── fecha
├── estado
├── subtotal_iva_12
├── subtotal_iva_0
├── iva
├── descuento
├── envio
├── total
└── metodo_pago

detallesorden (líneas)
├── detalle_id
├── orden_id (FK)
├── producto_id
├── variante_id
├── cantidad
├── precio_unitario
├── subtotal
└── iva_aplicado
```

3. **Estados de Orden**
   - Pendiente
   - Confirmada
   - En proceso
   - Enviada
   - Entregada
   - Cancelada

4. **Cambio de Estado**
   - Endpoint específico
   - Notificación automática por email
   - Registro de cambios

### 🧾 Facturación Electrónica SRI (Ecuador)

**Sistema completo de facturación:**

- **Generación de Facturas Electrónicas**
  - Compatible con SRI Ecuador
  - Clave de acceso de 49 dígitos
  - Código de autorización con código de barras
  - Formato PDF con DOMPDF

- **Componentes de Factura:**
```
factura_electronica
├── factura_id
├── orden_id (FK)
├── numero_factura
├── fecha_emision
├── clave_acceso (49 dígitos)
├── codigo_autorizacion
├── subtotal_iva_12
├── subtotal_iva_0
├── iva
├── total
├── xml_firmado
└── pdf_generado
```

- **Datos de Facturación:**
  - RUC/Cédula
  - Razón social
  - Dirección fiscal
  - Email
  - Teléfono

- **Envío Automático:**
  - Email con PDF adjunto
  - Mailer: `EnviarFactura`

### 📧 Sistema de Notificaciones (6 Mailers)

1. **RecuperarClave** - Recuperación de contraseña
2. **PedidoGenerado** - Confirmación de pedido nuevo
3. **CambioEstadoPedido** - Notificación de cambio de estado
4. **EnviarFactura** - Envío de factura electrónica
5. **NuevoSuscriptor** - Confirmación de suscripción
6. **ContactoMail** - Respuesta a formulario de contacto

### 👥 Gestión de Clientes

**Funcionalidades:**
- CRUD completo de clientes
- Datos de contacto (email, teléfono, dirección)
- Múltiples direcciones de entrega
- Datos de facturación separados
- Dashboard del cliente con historial
- Tablero de estadísticas

### 🌍 Sistema de Ubicaciones Geográficas

**Estructura jerárquica:**
- **Provincias**: División principal
- **Ciudades**: Municipios por provincia
- **Parroquias**: Subdivisiones

**Uso:**
- Direcciones de entrega
- Cálculo de costos de envío
- Validación de cobertura

### 📊 Reportes y Análisis

**Endpoint de reportes:**
- `GET /api/orden/reportes/reporte`
- Filtros por fecha, estado, cliente
- Totales de ventas
- Estadísticas de productos vendidos

### 🎨 Gestión de Contenido

- **Banners/Sliders**: Página principal
- **Ofertas Especiales**: Promociones
- **Publicidad**: Gestión de ads
- **Nosotros**: Información de empresa
- **Contactos**: Formulario de contacto

## 🔌 API Endpoints Principales

### Autenticación
```
POST   /api/login              # Login admin
POST   /api/loginPortal        # Login cliente
POST   /api/register           # Registro
POST   /api/logout             # Logout
POST   /api/refresh            # Refresh token
POST   /api/recuperar          # Recuperar password
GET    /api/usuariosClientes   # Listar clientes (auth)
```

### Productos
```
GET    /api/producto           # Listar productos
POST   /api/producto           # Crear producto
GET    /api/producto/{id}      # Obtener producto
POST   /api/producto/{id}      # Actualizar producto
DELETE /api/producto/{id}      # Eliminar producto
GET    /api/categorias         # Listar categorías
GET    /api/marca              # Listar marcas
```

### Variantes
```
GET    /api/variantes                  # Listar variantes
POST   /api/variantes                  # Crear variante
GET    /api/variantes/{id}             # Obtener variante
GET    /api/variantes/product/{id}     # Por producto
```

### Carrito
```
GET    /api/detallescarrito            # Items carrito
POST   /api/detallescarrito            # Agregar item
GET    /api/detallescarrito/{id}       # Item específico
POST   /api/detallescarrito/{id}       # Actualizar cantidad
DELETE /api/detallescarrito/{id}       # Eliminar item
```

### Órdenes
```
GET    /api/orden                      # Listar órdenes
POST   /api/orden                      # Crear orden
GET    /api/orden/{id}                 # Obtener orden
GET    /api/orden/cliente/{id}         # Por cliente
PUT    /api/orden/{id}                 # Actualizar
POST   /api/orden/cambiarEstado/{id}   # Cambiar estado
GET    /api/orden/reportes/reporte     # Reportes
```

### Facturación
```
GET    /api/facturaelectronica         # Listar facturas
POST   /api/facturaelectronica         # Crear factura
GET    /api/facturaelectronica/{id}    # Obtener factura
GET    /api/facturaelectronica/generadoc/{id} # Generar PDF
```

### Ubicaciones
```
GET    /api/provincia                  # Provincias
GET    /api/ciudades                   # Ciudades
GET    /api/direccionesentrega         # Direcciones
GET    /api/direccionesentrega/cliente/{id} # Por cliente
```

## 🔧 Instalación

### Prerrequisitos

- PHP 7.3+ o 8.0+
- Composer
- MySQL 5.7+
- Node.js (opcional, para assets)

### Pasos

1. Clonar repositorio
```bash
git clone https://github.com/dannyggg3/api_pwa.git
cd api_pwa
```

2. Instalar dependencias
```bash
composer install
```

3. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Configurar base de datos en `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_pwa
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=[generado automáticamente]
JWT_TTL=60
```

5. Ejecutar migraciones
```bash
php artisan migrate
php artisan db:seed
```

6. Configurar email en `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@api-pwa.com"
MAIL_FROM_NAME="${APP_NAME}"
```

7. Iniciar servidor
```bash
php artisan serve
# API disponible en http://localhost:8000
```

## 💻 Uso

### Ejemplo: Autenticación

```bash
curl -X POST http://localhost:8000/api/loginPortal \
  -H "Content-Type: application/json" \
  -d '{
    "email": "cliente@example.com",
    "password": "password123"
  }'
```

Respuesta:
```json
{
  "correctProcess": true,
  "data": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "cliente@example.com",
    "rol_id": 2
  },
  "authorisation": {
    "token": "eyJ0eXAiOiJKV1QiLC...",
    "type": "bearer"
  }
}
```

### Ejemplo: Crear Orden

```bash
curl -X POST http://localhost:8000/api/orden \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "cliente_id": 1,
    "metodo_pago": "tarjeta",
    "direccion_entrega_id": 1,
    "datos_facturacion_id": 1,
    "productos": [
      {
        "variante_id": 5,
        "cantidad": 2,
        "precio_unitario": 25.50
      }
    ]
  }'
```

## 📈 Estadísticas del Proyecto

| Métrica | Valor |
|---------|-------|
| Controladores | 34 |
| Modelos Eloquent | 33 |
| Mail Classes | 6 |
| API Endpoints | 100+ |
| Líneas routes/api.php | 371 |
| Tablas de BD | 30+ |

## 🔒 Seguridad

- JWT con expiración y refresh
- CORS configurado
- Validación de input en todos los endpoints
- Rate limiting en rutas API
- Sanitización de datos
- HTTPS recomendado en producción

## 🧪 Testing

```bash
php artisan test
php artisan test --coverage
```

## 🚀 Despliegue

### Producción

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Docker (Sail)

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

## 📄 Licencia

Este proyecto es parte del portafolio de desarrollo de dannyggg3.

## 👤 Autor

**dannyggg3**
- GitHub: [@dannyggg3](https://github.com/dannyggg3)

---

⭐ Si este proyecto te fue útil, considera darle una estrella
