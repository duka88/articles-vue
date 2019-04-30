export default class Gate{

    constructor(user){
        this.user = user;
    }
    
    isAuth(){
    	 return this.user;
    }

    isAdmin(){
        return this.user.type === 'admin';
    }

    isUser(){
        return this.user.type === 'user';
    }

	idUser(){
		return this.user.id;
	}
}