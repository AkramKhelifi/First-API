document.getElementById('loadResearchers').addEventListener('click', fetchResearchers);

function fetchResearchers() {
    fetch('http://localhost/RestChercheur/api/chercheur.php?action=qualifiedResearchers')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            const list = document.getElementById('researchersList');
            list.innerHTML = '';
            data.data.forEach(researcher => {
                const listItem = document.createElement('li');
                listItem.textContent = `${researcher.nom} ${researcher.prenom}`;
                list.appendChild(listItem);
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des chercheurs:', error));
}
