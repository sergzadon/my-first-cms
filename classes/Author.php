program bancomat2;
   type intArr = array[1..10] of integer; // обьявление типов
   type strArr = array[1..10] of string;

   var i, j, n, k, code, int, lenArr,first, valMon,countMoney,balance : integer;
     s,sum, str_val, valueMoney,Money,valM,balAm : string;
     lab : boolean;
     arrMoney : intArr;
     arr_res : strArr;
begin
  lab := True;

  // заполнение массива купюр
   arrMoney[1] := 50;
   arrMoney[2] := 100;
   arrMoney[3] := 200;
   arrMoney[4] := 500;
   arrMoney[5] := 1000;
   arrMoney[6] := 2000;
   arrMoney[7] := 5000;


  //arrMoney = [50,100,200,500,1000,2000,5000]
  //arrMoney = [5000]
  // arr_res = []
  i := 0;
  lenArr := high(arrMoney);
  lab := True;
  while(lab) do
  begin
      writeln('input sum:');
      readln(sum);
      Val(sum[1], first, code);
      Val(sum, int, code);
      if(first > 0 ) and (int mod 100 = 0 ) or (int mod 100 = arrMoney[0]) then
      begin
          for j:=1 to high(arr_res) do // делаем значения массива равными 0
              arr_res[j] := '';
          //arr_res = []
          while(i < high(arrMoney)) do
          begin
              Str(arrMoney[lenArr], str_val);
              valueMoney := str_val;
              Val(valueMoney,valMon,code);
              if(length(sum) >= length(valueMoney)) and (int >= valMon) then
              begin
                  countMoney := int div valMon;
 //            //остаток
                  balance := int mod valMon;
                  Str(countMoney, Money);
                  Str(valMon, valM);
                  s := Money +' '+'по'+' '+ valM;
                  arr_res[k] := s;
                  s := '';
                  Str(balance,balAm);
                  sum := balAm;
                  i += 1;
                  lenArr -= 1;

              end
              else
              begin
                  lenArr -= 1;
                  i += 1;
              end;
          end;
          i := 0;
          lab := False;
          writeln(arr_res[1]);
          writeln('end');
       end;
  end;
end.   

