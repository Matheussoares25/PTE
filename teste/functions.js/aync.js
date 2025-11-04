async function esperar(){
    let aguardo = new Promise((resolve) => setTimeout(() => resolve('Resolvido'), 2000));
    let resultado = await aguardo;

    return resultado;
}