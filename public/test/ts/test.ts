function concat(left:string , right:string):string {
  return left+right;
}

let hello:string = concat(1, "world");
let hello:number = concat(1, "world");
let hello:boolean = concat(1, "world");
let hello:null = concat(1, "world");
let hello:undefined = concat(1, "world");
let hello:bigint = concat(1, "world");
let hello:[] = concat(1, "world");
let hello:{} = concat(1, "world");

console.log(hello);


