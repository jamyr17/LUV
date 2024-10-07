# Definición de géneros y orientaciones sexuales (hechos)
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

# Reglas de afinidad como diccionario
afinidades = {
    'Heterosexual': {
        'Masculino': ['Femenino'],
        'Femenino': ['Masculino']
    },
    'Homosexual': {
        'Masculino': ['Masculino'],
        'Femenino': ['Femenino']
    },
    'Bisexual': generos,
    'Pansexual': generos,
    'Polisexual': generos,
    'Omnisexual': generos,
    'Fraysexual': generos,
    'Asexual': ['Amistad'],
    'Demisexual': ['Amistad'],
    'Grisexual': ['Amistad'],
    'Autosexual': generos,
    'Androsexual': ['Masculino'],
    'Ginesexual': ['Femenino'],
    'Skoliosexual': ['No binario']
}

# Función para obtener afinidades
def obtenerAfinidad(genero, orientacion):
    if orientacion not in orientacionesSexuales:
        return ['Indefinido']

    # Obtener las afinidades para la orientación
    if orientacion in afinidades:
        if isinstance(afinidades[orientacion], dict):
            return afinidades[orientacion].get(genero, ['Indefinido'])
        else:
            return afinidades[orientacion]
    
    return ['Indefinido']

# Ejemplo de uso
def perfilBusqueda(nombre, genero, orientacion):
    afinidades_resultantes = obtenerAfinidad(genero, orientacion)
    return f"{nombre} busca personas de género(s): {', '.join(afinidades_resultantes)}."

# Pruebas
print(perfilBusqueda("Chris", "Masculino", "Heterosexual"))  # Busca Femenino
print(perfilBusqueda("Alex", "No binario", "Skoliosexual"))  # Busca No binario
print(perfilBusqueda("Jamie", "Femenino", "Bisexual"))       # Busca a todos los géneros
print(perfilBusqueda("Taylor", "Masculino", "Homosexual"))   # Busca Masculino
