const todosElem = document.getElementById('ft_list');
const saveTodos = () => {
  document.cookie = 'todos=' + JSON.stringify(todos);
};
const createTodo = (id, text) => {
  todos[id] = text;

  const newTodo = document.createElement('div');
  newTodo.innerText = text;
  newTodo.dataset.id = id;
  newTodo.classList.add('todo');

  id += 1;
  
  todosElem.insertBefore(newTodo, todosElem.firstChild);
  saveTodos();

  newTodo.addEventListener('click', function (e) {
    e.preventDefault();

    const ret = {};

    const keys = Object.keys(todos);
    for (let i = keys.length - 1; i >= 0; i--) {
      const key = keys[i];
      if (!todos.hasOwnProperty(key)) {
        continue;
      }

      if (key === this.dataset.id) {
        continue;
      }

      ret[key] = todos[key];
    }

    todos = ret;
    this.remove();

    saveTodos();
  });
};
const parseTodos = () => {
  const cookieArr = document.cookie.split(';');
  const cookieArrReduced = cookieArr.reduce((acc, cur) => {
    cur = cur.trim();
    if (cur.startsWith('todos=')) {
      acc.push(cur);
    }
    return acc;
  }, []);

  if (!cookieArrReduced.length) {
    todos = {};
    return;
  }

  const data = cookieArrReduced[0].substr(6);
  if (data === '') {
    todos = {};
    return;
  }
  
  try {
    todos = JSON.parse(data);
  } catch {
    todos = {};
    return;
  }

  const keys = Object.keys(todos).reverse();
  for (let i = keys.length - 1; i >= 0; i--) {
    const key = keys[i];
    if (!todos.hasOwnProperty(key)) {
      continue;
    }

    createTodo(key, todos[key]);
    if (key > id) {
      id = key + 1;
    }
  }
}

// Then scripts
let todos;
let id = 0;

parseTodos();

const create = document.getElementById('create');
create.addEventListener('click', e => {
  e.preventDefault();

  const text = prompt('Enter your todo task');

  if (text === '') {
    return;
  }

  createTodo(id, text);
  id += 1;
});
