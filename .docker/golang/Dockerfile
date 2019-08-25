FROM golang:1.12.6-stretch as buildando

WORKDIR /go/src/app

COPY src/app/app.go .

RUN go get -d -v ./...
RUN go install -v ./...

FROM scratch

WORKDIR /bin

COPY --from=buildando /go/bin/app /bin

CMD ["/bin/app"]

