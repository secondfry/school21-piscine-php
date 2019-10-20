const todosElem = $('#ft_list');
const saveTodo = (id, text) => {
  $.post('/insert.php', { id, text });
}
const removeTodo = id => {
  $.post('/delete.php', { id });
}
const createTodo = (id, text, save) => {
  todos[id] = text;

  const newTodo = $(`<div class="todo" data-id="${id}">${text}</div>`);
  todosElem.prepend(newTodo);

  id += 1;

  if (save) {
    saveTodo(id, text);
  }

  newTodo.click(function (e) {
    e.preventDefault();

    const ret = {};

    const keys = Object.keys(todos);
    for (let i = keys.length - 1; i >= 0; i--) {
      const key = keys[i];
      if (!todos.hasOwnProperty(key)) {
        continue;
      }

      if (parseInt(key) === $(this).data('id')) {
        continue;
      }

      ret[key] = todos[key];
    }

    todos = ret;
    $(this).remove();

    removeTodo($(this).data('id'));
  });
};
const parseTodos = () => {
  $.get('/select.php')
    .done(data => {
      console.log(data);
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

        createTodo(key, todos[key], false);
        console.log(key);
        if (key > id) {
          id = parseInt(key) + 1;
        }
      }
    });
}

// Then scripts
let todos;
let id = 0;

parseTodos();

const create = $('#create');
create.click(e => {
  e.preventDefault();

  const text = prompt('Enter your todo task');

  if (text === '') {
    return;
  }

  createTodo(id, text, true);
  id += 1;
});
