<template>
  <div >
    <div class="container-fluid d-flex justify-content-end">
      <button v-if="auth"  @click="loginFormFn" 
               class="btn btn-primary mr-3 mb-3 d-flex">Login</button>
      <button v-show="!auth" @click="registerFormFn" 
               class="btn btn-primary mb-3 d-flex">Register</button>  
      <button v-if="auth" @click="logOut" 
               class="btn btn-primary mb-3 d-flex">Logout</button>                
    </div>
     <div class="container-fluid">
      <div class="row d-flex justify-content-center">
        <div  v-if="loginForm" class="col-6">
          
              <div class="form-group">
                <h2 class="login-heading">Login</h2>
                <form action="#" @submit.prevent="login">

                  <div class="form-group">
                    <label for="username">Username/Email</label>
                    <input type="email" name="username" id="username" class="form-control" v-model="user.username">
                  </div>

                  <div class="form-group mb-more">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" v-model="user.password">
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn-submit mr-3">Login</button>
                    <button @click="cancelForm" class="btn-submit">Cancel</button>
                  </div>

                </form>
              </div>
        </div>
        <div v-if="registerForm" class="col-6">
         
          <div  class="form-group">
            <h2 class="login-heading">Register</h2>
            <form action="#" @submit.prevent="register">

              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" v-model="user.name">
              </div>

              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" v-model="user.email">
              </div>

              <div class="form-group mb-more">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" v-model="user.password">
              </div>

              <div class="form-group">
                <button type="submit" class="btn-submit mr-3">Create Account</button>
                <button @click="cancelForm" class="btn-submit">Cancel</button>
              </div>

            </form>
          </div>
        </div>
      </div> 
     </div>
     <div class="container-fluid">
      <h2>Articles </h2>
      <button @click="show=!show" v-if="show === false " class="btn btn-success btn-block my-2">Add Article</button>
      <form v-if="show" @submit.prevent="addArticle" class="mb-3">
        <div v-for="error in errors">{{error}}</div>
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Title" v-model="article.title">

        </div>
        <div class="form-group">
          <textarea class="form-control" placeholder="Body" v-model="article.body"></textarea>
        </div>
        <button type="submit" class="btn btn-success btn-block">Save</button>
      </form>
      <button v-if="show" @click="clearForm()" class="btn btn-danger btn-block mb-2">Cancel</button>
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchArticles(pagination.prev_page_url)">Previous</a></li>

          <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
      
          <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchArticles(pagination.next_page_url)">Next</a></li>
        </ul>
      </nav>
      <div class="card card-body mb-2" v-for="article in articles" v-bind:key="article.id">
        <h3>{{ article.title }}</h3>
        <p>{{ article.body }}</p>
        <hr>
        
              <button v-if="authUser.role === 'admin' || authUser.id ==article.user_id" 
              @click="editArticle(article)" class="btn btn-warning mb-2">Edit</button>
              <button v-if="authUser.role === 'admin' || authUser.id ==article.user_id"
               @click="deleteArticle(article.id)" class="btn btn-danger">Delete</button>
        
      </div>
     </div> 
  </div>
</template>

<script>
export default {
  data() {
    return {
      errors: [],      
      articles: [],
      article: {
        id: '',
        title: '',
        body: '',
        user_id: ''
      },
      user:{
        name: '',
        token: localStorage.getItem('access_token') || null,
        username: '',
        password: '',
      },
      authUser:{
        id: '',
        role: ''
      },
      article_id: '',
      pagination: {},
      edit: false,
      show: false,
      loginForm: false,
      registerForm: false,
      
    };
  },

  created() {
    console.log(this.auth());
    console.log(this.user.token);
    this.getAuth();
    this.auth();
    this.fetchArticles();    
  },

  methods: {

    auth(){
      if(this.user.token){
        return  true;
      }else{
        return false;
      }
    },
    getAuth(){
       axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.user.token;


       axios.get('/api/user')
       .then(response => {            
        this.authUser.id = response.data.id;
        this.authUser.role = response.data.role;
        this.article.user_id = response.data.id;
      })
      .catch(error => {
        console.log(error)
      })
    },
    fetchArticles(page_url) {
      let vm = this;
      page_url = page_url || '/api/articles';
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.articles = res.data;
          vm.makePagination(res.meta, res.links);
        })
        .catch(errors => console.log(errors));
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };

      this.pagination = pagination;
    },
    deleteArticle(id) {
      if (confirm('Are You Sure?')) {
        fetch(`api/article/${id}`, {
          method: 'delete'
        })
          .then(res => res.json())
          .then(data => {
            alert('Article Removed');
            this.fetchArticles();
          })
          .catch(err => console.log(err));
      }
    },
    addArticle() {
      if (this.edit === false) {
        // Add
        fetch('api/article', {
          method: 'post',
          body: JSON.stringify(this.article),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Article Added');
            this.fetchArticles();
          })
          .catch(err => console.log(err));
      } else {
        // Update
        fetch('api/article', {
          method: 'put',
          body: JSON.stringify(this.article),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Article Updated');
            
            this.fetchArticles();
            clearForm();
          })
          .catch(err => console.log(err));
      }
    },
    editArticle(article) {
      this.edit = true;

      this.article.id = article.id;
      this.article.article_id = article.id;
      this.article.title = article.title;
      this.article.body = article.body;
       this.show = true;
    },
    clearForm() {
      this.edit = false;
      this.article.id = null;
      this.article.article_id = null;
      this.article.title = '';
      this.article.body = '';
      this.show = false;
    },
    login(){
      axios.post('/api/login',{
        username: this.user.username,
        password: this.user.password
      }).then(response => {
        const token = response.data.access_token;
        
        localStorage.setItem('access_token', token);
        this.loginForm = false;
      })
      .catch(error => {
        console.log(error)
      })
    },
    register(){
       axios.post('/api/register',{
        name: this.user.name,
        email: this.user.email,
        password: this.user.password
      }).then(response => {      
        this.registerForm = false;
        this.loginForm = true;
      })
      .catch(error => {
        console.log(error)
      })
    },    
    logOut(){
          axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.user.token

      if(this.auth()){
         axios.post('/api/logout')
               .then(response => {
                localStorage.removeItem('access_token');
               })
               .catch(error => {
                localStorage.removeItem('access_token');
                console.log(error);
               });
          this.auth();     
      }

    },
    loginFormFn(){
      
      this.loginForm = true;
      this.registerForm = false;
    },
    registerFormFn(){
      this.user.name = '';
      this.user.email = '';
      this.user.password = '';
      this.loginForm = false;
      this.registerForm = true;
    },
    cancelForm(){
      this.loginForm = false;
      this.registerForm = false;
    }
  }
};
</script>
