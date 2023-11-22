# restApiDespesas
Repositório utilizado para fazer um rest api de despesas.

# rotas de acesso

> ✅ POST: **/api/register**

body:
```json:
    {
        "name":string,
        "email":string,
        "password":string
    }
```


> 💡 POST: **/api/login**

body:
```json:
    {
        "email":string,
        "password":string
    }
```

## Rotas protegidas

> 📖 GET: **/api/despesa/{id?}**

body:
```json:
    header: Beare Token
```

> 💾 POST: **/api/despesa**

body:
```json:
    header: Beare Token
    {
        "descricao": string,
        "data": datetime,
        "valor":float
    }
```

> 🔄️ PUT: **/api/despesa/{id}**

body:
```json:
    header: Beare Token
    {
        "descricao": string,
        "data": datetime,
        "valor":float
    }
```

> 🗑️ DELETE: **/api/despesa/{id}**

body:
```json:
    header: Beare Token
```
