<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<style>
.btn-circle{
    border-radius: 50%;
    width: 3vw;
    height: 5vw;
    margin: 1rem;
    font-size: 2.2rem;
}
.btn-circle-sm{
    border-radius: 50%;
    width:2vw;
    height: 4vw;
    margin: .8rem;
    font-size: 1rem;
}
</style>

@php
    $todos = new Marto\Todos\Todos(\Route::currentRouteName());
    $todos->ifFileExistOrCreate()->dataExistOrSetFirstPage();
@endphp

<div id="todos" class="bg-secondary overflow-auto" style="position:absolute;top: 10vh;left: 10vw;width: 80vw;min-height: 5vw;border: 2px solid black;z-index: 999;color: white;display: none;max-height:80vh;">
    <strong class="bg-warning rounded pl-1 pt-1 mb-2 ml-2 text-dark">Задачки за страница "@{{routeName}}": &nbsp;</strong>
    <ul  class="ml-5 mr-5 mt-2">
        <li v-for="(todo, index) in pages[routeName]" class="list-unstyled">
            <div v-if="todo" class="form-group row bg-dark">
                <h3 class="col-8 mr-auto rounded">@{{todo}}</h3>
                <button @click="editTodo(index)" class="btn btn-circle btn-primary col-1 ml-auto">&#9998;</button>
                <button @click="deleteTodo(index)" class="btn btn-circle btn-danger col-1">&#9249;</button>
            </div>
            <div v-else class="form-group row">
                <textarea v-model="newTodoBody" @keyup="pressKyesInTodoForm($event)" rows="1" class="form-control col-8" placeholder="Напиши задача..." autofocus></textarea>
                <button @click="storeNewTodo(index)" class="btn btn-circle-sm btn-success col-1 ml-2" :disabled="storingTodoIsForbidden">&#128190;</button>
                <button @click="cancelWritingTodo()" class="btn btn-circle-sm btn-danger col-1">&#9249;</button>
            </div>
        </li>
        <li @click="todoCreate()" v-if="willWriteTodo" class="btn btn-success">попълни нова задача</button></li>
    </ul>
</div>
<script>
    let visible = false;

    window.onkeydown = listenForCtrlKeyDown;

    function listenForCtrlKeyDown(e){
        if(e.ctrlKey && e.shiftKey){
            if(visible){
                document.getElementById('todos').style.display = 'none';
                visible = false;
            }else{
                document.getElementById('todos').style.display = 'block';
                visible = true;
            }
        }
    }

    new Vue({
        el: '#todos',
        data: {
            newTodoBody: '',
            willWriteTodo: true,
            storingTodoIsForbidden: true,
            routeName: '{!! $todos->routeName !!}',
            pages: {!! $todos->getData() !!}
        },
        methods: {
            todoCreate(){
                this.willWriteTodo = false;
                this.pages[this.routeName].push('');
            },
            cancelWritingTodo(){
                this.storingTodoIsForbidden=true;
                this.willWriteTodo = true;
                this.newTodoBody = '';
                this.pages[this.routeName].pop();
                this.updateOnTheServer();
            },
            pressKyesInTodoForm(e){
                if(e.target.value){
                    this.storingTodoIsForbidden=false;
                }else{
                    this.storingTodoIsForbidden=true;
                }
            },
            storeNewTodo(index){
                this.willWriteTodo = true;
                this.storingTodoIsForbidden=true;
                this.pages[this.routeName].splice(index, 1);
                this.pages[this.routeName].push(this.newTodoBody);
                this.newTodoBody = '';
                this.updateOnTheServer();
            },
            deleteTodo(index){
                this.pages[this.routeName].splice(index, 1);
                this.updateOnTheServer();
            },
            editTodo(index){
                let todo = this.pages[this.routeName][index];
                this.pages[this.routeName].splice(index, 1);
                this.todoCreate();
                this.newTodoBody = todo;
                this.storingTodoIsForbidden=false;
            },
            updateOnTheServer(){
                axios.post('/todos', this.pages).then(function(response){
                    this.pages = response.data;
                }).catch(function(error){
                    console.log(error.data);
                });
            }
        }
    });
</script>