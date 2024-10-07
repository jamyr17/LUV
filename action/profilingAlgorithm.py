# Declaración de hechos (géneros y orientaciones)
generos = [
    'Masculino', 'Femenino', 'No binario', 'Género fluido', 
    'Agénero', 'Bigénero', 'Género queer', 'Demigénero', 
    'Intergénero', 'Dos espíritus'
]

orientacionesSexuales = [
    'Heterosexual', 'Homosexual', 'Bisexual', 
    'Pansexual', 'Asexual', 'Demisexual', 'Sapiosexual', 
    'Autosexual', 'Androsexual', 'Ginesexual', 
    'Polisexual', 'Skoliosexual', 'Omnisexual', 
    'Grisexual', 'Fraysexual'
]

# Reglas declarativas de afinidades
afinidades = {
    'Heterosexual': {
        'Masculino': {'busca': ['Femenino']},
        'Femenino': {'busca': ['Masculino']}
    },
    'Homosexual': {
        'Masculino': {'busca': ['Masculino']},
        'Femenino': {'busca': ['Femenino']}
    },
    'Bisexual': {'busca': generos},
    'Pansexual': {'busca': generos},
    'Polisexual': {'busca': generos},
    'Omnisexual': {'busca': generos},
    'Fraysexual': {'busca': generos},
    'Asexual': {'busca': ['Amistad']},
    'Demisexual': {'busca': ['Amistad']},
    'Grisexual': {'busca': ['Amistad']},
    'Autosexual': {'busca': generos},
    'Androsexual': {'busca': ['Masculino']},
    'Ginesexual': {'busca': ['Femenino']},
    'Skoliosexual': {'busca': ['No binario']}
}

# Función que interpreta reglas de afinidad
def obtenerAfinidad(genero, orientacion):
    regla = afinidades.get(orientacion, {})
    afinidades_genero = regla.get('busca', ['Indefinido'])
    
    if isinstance(afinidades_genero, dict):  # Si hay reglas específicas para el género
        return afinidades_genero.get(genero, ['Indefinido'])
    return afinidades_genero

# Ejemplo de uso con salida declarativa
def perfilBusqueda(nombre, genero, orientacion):
    afinidades_resultantes = obtenerAfinidad(genero, orientacion)
    return f"{nombre} busca personas de género(s): {', '.join(afinidades_resultantes)}."

# Pruebas
print(perfilBusqueda("Chris", "Masculino", "Heterosexual")) 
print(perfilBusqueda("Alex", "No binario", "Skoliosexual")) 
print(perfilBusqueda("Jamie", "Femenino", "Bisexual"))      
print(perfilBusqueda("Taylor", "Masculino", "Homosexual"))  
